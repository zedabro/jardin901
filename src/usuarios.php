<?php
include_once("../incluides/dashboard.php");
include_once("incluides/dbconexion.php");

function log_message($message) {
    $logfile = 'debug.log';
    file_put_contents($logfile, date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

$user_id = $_SESSION['user_id'] ?? null;
$jerarquia_id = $_SESSION['jerarquia_id'] ?? null;

$message = '';

// Procesamiento para editar o crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_mode'])) {
        // Editar usuario existente
        $id_usuario = $_POST['id_usuario'] ?? null;
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $documento = $_POST['documento'];
        $correo = $_POST['correo'];  
        $contraseña = $_POST['contraseña'];
        $jerarquia_id = $_POST['jerarquia_id'];

        log_message("Form values - id_usuario: $id_usuario, nombre: $nombre, apellido: $apellido, documento: $documento, correo: $correo, jerarquia_id: $jerarquia_id");

        if ($id_usuario) {
            // Actualización de usuario existente
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, documento = ?, correo = ?, jerarquia_id = ?";
            $params = [$nombre, $apellido, $documento, $correo, $jerarquia_id];
            if ($contraseña) {
                $contraseña_encriptada = password_hash($contraseña, PASSWORD_BCRYPT);
                $sql .= ", contraseña = ?";
                $params[] = $contraseña_encriptada;
            }
            $sql .= " WHERE id_usuario = ?";
            $params[] = $id_usuario;

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Usuario editado con éxito.</div>';
                    log_message("Query executed successfully.");
                } else {
                    $message = '<div class="alert alert-danger">Error al editar usuario: ' . $stmt->error . '</div>';
                    log_message("Query error: " . $stmt->error);
                }
                $stmt->close();
            } else {
                $message = '<div class="alert alert-danger">Error preparando la consulta: ' . $conn->error . '</div>';
                log_message("Query preparation error: " . $conn->error);
            }
        } else {
            $message = '<div class="alert alert-danger">No se ha proporcionado ID de usuario válido para editar.</div>';
            log_message("Invalid user ID for editing.");
        }
    } else {
        // Crear nuevo usuario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $documento = $_POST['documento'];
        $correo = $_POST['correo'];  
        $contraseña = $_POST['contraseña'];
        $jerarquia_id = $_POST['jerarquia_id'];

        log_message("Form values - nombre: $nombre, apellido: $apellido, documento: $documento, correo: $correo, jerarquia_id: $jerarquia_id");

        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, apellido, documento, correo, contraseña, jerarquia_id) VALUES (?, ?, ?, ?, ?, ?)";
        $contraseña_encriptada = password_hash($contraseña, PASSWORD_BCRYPT);
        $params = [$nombre, $apellido, $documento, $correo, $contraseña_encriptada, $jerarquia_id];

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Usuario creado con éxito.</div>';
                log_message("Query executed successfully.");
                // Limpiar los campos del formulario después de la creación exitosa
                $_POST = array();
            } else {
                $message = '<div class="alert alert-danger">Error al crear usuario: ' . $stmt->error . '</div>';
                log_message("Query error: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $message = '<div class="alert alert-danger">Error preparando la consulta: ' . $conn->error . '</div>';
            log_message("Query preparation error: " . $conn->error);
        }
    }
}

// Procesamiento para cargar usuario existente
$usuario_editar = null;
if (isset($_GET['id'])) {
    $id_usuario_editar = $_GET['id'];

    $sql_usuario = "SELECT nombre, apellido, documento, correo, jerarquia_id FROM usuarios WHERE id_usuario = ?";
    $stmt_usuario = $conn->prepare($sql_usuario);
    if ($stmt_usuario) {
        $stmt_usuario->bind_param("i", $id_usuario_editar);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();
        if ($result_usuario->num_rows > 0) {
            $usuario_editar = $result_usuario->fetch_assoc();
        } else {
            $message = '<div class="alert alert-danger">No se encontró ningún usuario con el ID proporcionado.</div>';
            log_message("No user found with ID: $id_usuario_editar");
        }
        $stmt_usuario->close();
    } else {
        $message = '<div class="alert alert-danger">Error preparando la consulta para cargar usuario: ' . $conn->error . '</div>';
        log_message("Query preparation error for loading user: " . $conn->error);
    }
}

$sql_jerarquias = "SELECT jerarquia_id, nombre FROM jerarquias";
$result_jerarquias = $conn->query($sql_jerarquias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/usuarios.css">
    <style>
        .main-container {
            margin-left: 65px;
            padding: 20px;
            flex: 1;
        }
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 2%;
        }
        .table thead th {
            background-color: #f2f2f2;
        }
        button {
            margin: 2px 0px;
        }
        h2 {
            text-align: center;
        }
        @media (max-width: 768px) {
    .main-container {
        margin-left: 0px;

    }
    .creacion {
        display: none;
    }
}
@media (max-width: 576px) {
    .main-container {
        margin-left: 0px;
    }
    .creacion {
        display: none;
    }
}
    </style>
</head>
<body>
<div id="content">
<div class="container">
    <h1 class="text-center">Cargar Usuario</h1>

    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>

    <form method="POST" action="usuarios.php">
        <?php if (isset($usuario_editar)): ?>
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario_editar); ?>">
            <input type="hidden" name="edit_mode" value="1">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario_editar['nombre'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario_editar['apellido'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
             <label for="documento">n-documento:</label>
             <input type="text" class="form-control" id="documento" name="documento" 
             value="<?php echo htmlspecialchars($usuario_editar['documento'] ?? ''); ?>" 
             pattern="\d{8}" minlength="8" maxlength="8" required>
            </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correo">Correo:</label> 
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario_editar['correo'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jerarquia_id">Jerarquia:</label>
                    <select class="form-control" id="jerarquia_id" name="jerarquia_id" required>
                        <option value="">Seleccione una jerarquía</option>
                        <?php
                        while ($row_jerarquia = $result_jerarquias->fetch_assoc()) {
                            $selected = ($row_jerarquia['jerarquia_id'] == ($usuario_editar['jerarquia_id'] ?? '')) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_jerarquia['jerarquia_id']) . '" ' . $selected . '>' . htmlspecialchars($row_jerarquia['nombre']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-dark btn-block"><?php echo isset($usuario_editar) ? 'Editar Usuario' : 'Cargar Usuario'; ?></button>
            </div>
        </div>
    </form>
</div>
<?php
include_once("incluides/dbconexion.php");

$sql_usuarios = "SELECT usuarios.id_usuario, usuarios.nombre, usuarios.apellido, usuarios.documento, usuarios.correo, jerarquias.nombre AS jerarquia_nombre
                FROM usuarios
                JOIN jerarquias ON usuarios.jerarquia_id = jerarquias.jerarquia_id";
$result_usuarios = $conn->query($sql_usuarios);
?>

<?php if ($result_usuarios && $result_usuarios->num_rows > 0): ?>
    <div class="table-container">
    <h2 class="mt-5">Usuarios Cargados</h2>
        <table id="userTable" class="display">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th  class="creacion">Correo</th>
                    <th class="creacion">Jerarquia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                    <tr id="<?php echo $usuario['id_usuario']; ?>">
                        <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['documento']); ?></td>
                        <td  class="creacion"><?php echo htmlspecialchars($usuario['correo']); ?></td>
                        <td  class="creacion"><?php echo htmlspecialchars($usuario['jerarquia_nombre']); ?></td>
                        <td>
                            <a href="usuarios.php?id=<?php echo htmlspecialchars($usuario['id_usuario']); ?>" class="btn btn-sm btn-warning">Editar</a>
                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(event, <?php echo $usuario['id_usuario']; ?>)">Borrar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>No se han encontrado usuarios.</p>
<?php endif; ?>

<?php $conn->close(); ?>
</div>

<script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "language": {
                    url: '//cdn.datatables.net/plug-ins/2.1.2/i18n/es-AR.json',
                },
                "searching": true, 
                "paging": true, 
                "ordering": true 
            });
    });

    function confirmarEliminacion(event, id) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás recuperar este usuario después de eliminarlo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'scriptsphp/eliminar_usuario.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    $('#' + id).remove();
                    Swal.fire(
                        'Eliminado!',
                        'El usuario ha sido eliminado.',
                        'success'
                    );
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire(
                        'Error!',
                        'Hubo un problema al eliminar el usuario. Por favor, inténtalo de nuevo.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
