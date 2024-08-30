<?php
include_once("../incluides/dashboard.php");

$servername = "bvz7k1ksxz11rnbj0lj8-mysql.services.clever-cloud.com";
$username = "uhtj264qooomsf9c";
$password = "npnnZIsTN3dTbpbujEWD";
$database = "bvz7k1ksxz11rnbj0lj8";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$message = '';

$sql = "SELECT sala_id, titulo, descripcion, img_sala FROM salas";
$result = $conn->query($sql);

$sql_salas = "SELECT sala_id, titulo FROM salas";
$result_salas = $conn->query($sql_salas);

$sql_maestros = "SELECT id_usuario, nombre, apellido FROM usuarios WHERE jerarquia_id = 3";
$result_maestros = $conn->query($sql_maestros);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sala_id = isset($_POST['sala_id']) ? $_POST['sala_id'] : null;
    $maestro_id = isset($_POST['maestro_id']) ? $_POST['maestro_id'] : null;

    if ($sala_id && $maestro_id) {
        $sql_existencia = "SELECT * FROM salas_maestros WHERE sala_id = $sala_id AND id_usuario = $maestro_id";
        $result_existencia = $conn->query($sql_existencia);

        if ($result_existencia && $result_existencia->num_rows > 0) {
            $message = 'exist'; // Mensaje para indicar que la asignación ya existe
        } else {
            $sql_insertar = "INSERT INTO salas_maestros (sala_id, id_usuario) VALUES ($sala_id, $maestro_id)";

            if ($conn->query($sql_insertar) === TRUE) {
                $message = 'success'; // Mensaje para indicar éxito
            } else {
                $message = 'error'; // Mensaje para indicar error
            }
        }
    } else {
        $message = 'missing'; // Mensaje para indicar que faltan datos
    }
}
$conn->close();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Maestros</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- Estilos personalizados -->
    <style>
        .container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        h3 {
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>
<div id="content" class="container">
    <center>
        <h3>Asignar Maestro a Sala</h3>
        <form action="asignarmaestro.php" method="post">
            <div class="form-group">
                <label for="sala_id">Seleccionar Sala:</label>
                <select name="sala_id" id="sala_id" class="form-control">
                    <?php
                    if ($result_salas->num_rows > 0) {
                        while ($row = $result_salas->fetch_assoc()) {
                            echo '<option value="' . $row['sala_id'] . '">' . htmlspecialchars($row['titulo']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="maestro_id">Seleccionar Maestro:</label>
                <select name="maestro_id" id="maestro_id" class="form-control">
                    <?php
                    if ($result_maestros->num_rows > 0) {
                        while ($row = $result_maestros->fetch_assoc()) {
                            echo '<option value="' . $row['id_usuario'] . '">' . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-custom">Asignar Maestro</button>
        </form>
    </center>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
    var message = "<?php echo $message; ?>";
    if (message === 'success') {
        swal("¡Éxito!", "Maestro asignado con éxito.", "success");
    } else if (message === 'exist') {
        swal("Atención", "La asignación ya existe.", "warning");
    } else if (message === 'error') {
        swal("Error", "Error al guardar la asignación.", "error");
    } else if (message === 'missing') {
        swal("Atención", "Selecciona una sala y un maestro.", "warning");
    }
</script>
</body>
</html>
