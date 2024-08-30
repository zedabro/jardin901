<?php
include_once("../incluides/dashboard.php");
include_once("incluides/dbconexion.php");

$target_dir_actividades = "uploads/actividades/";
$target_dir_novedades = "uploads/novedades/";
$message = '';

// Crear los directorios si no existen
if (!is_dir($target_dir_actividades)) {
    mkdir($target_dir_actividades, 0777, true);
}
if (!is_dir($target_dir_novedades)) {
    mkdir($target_dir_novedades, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];

    // Consulta para contar el número de registros
    $sql_count = $tipo === 'actividad' ? "SELECT COUNT(*) as count FROM actividades" : "SELECT COUNT(*) as count FROM novedades";
    $result_count = $conn->query($sql_count);
    $row_count = $result_count->fetch_assoc();

    if ($row_count['count'] >= 3) {
        $message = "<div class='alert alert-danger'>No se pueden cargar más de 3 " . $tipo . "s.</div>";
    } else {
        if (isset($_FILES['img_actividad']) && $_FILES['img_actividad']['error'] === UPLOAD_ERR_OK) {
            $img_file = $_FILES['img_actividad'];
            $imageFileType = strtolower(pathinfo($img_file['name'], PATHINFO_EXTENSION)); // Obtener la extensión del archivo
            $allowed_types = ['jpg', 'jpeg', 'png']; // Tipos permitidos

            // Verificar si el tipo de archivo es permitido
            if (in_array($imageFileType, $allowed_types)) {
                $target_dir = $tipo === 'actividad' ? $target_dir_actividades : $target_dir_novedades;
                $target_file = $target_dir . uniqid() . "_" . basename($img_file['name']);

                if (move_uploaded_file($img_file['tmp_name'], $target_file)) {
                    $table = $tipo === 'actividad' ? 'actividades' : 'novedades';
                    $column_img = $tipo === 'actividad' ? 'img_actividad' : 'img_novedad';
                    $sql = "INSERT INTO $table (titulo, descripcion, $column_img) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("sss", $titulo, $descripcion, $target_file);

                        if ($stmt->execute()) {
                            $message = "<div class='alert alert-success'>" . ucfirst($tipo) . " creada con éxito.</div>";
                        } else {
                            $message = "<div class='alert alert-danger'>Error al crear la " . $tipo . ": " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    } else {
                        $message = "<div class='alert alert-danger'>Error al preparar la consulta: " . $conn->error . "</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Error al cargar la imagen de la " . $tipo . ".</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Tipo de archivo no permitido. Solo se permiten archivos JPG y PNG en esta sección.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Error al cargar la imagen de la " . $tipo . ".</div>";
        }
    }
}

$sql_actividades = "SELECT * FROM actividades LIMIT 3";
$result_actividades = $conn->query($sql_actividades);

$actividades = [];
if ($result_actividades && $result_actividades->num_rows > 0) {
    while ($row = $result_actividades->fetch_assoc()) {
        $actividades[] = $row;
    }
}

$sql_novedades = "SELECT * FROM novedades LIMIT 3";
$result_novedades = $conn->query($sql_novedades);

$novedades = [];
if ($result_novedades && $result_novedades->num_rows > 0) {
    while ($row = $result_novedades->fetch_assoc()) {
        $novedades[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Carga de noticias/novedades</title>
</head>
<body>
<style>
.container {
    background-color: #fff;
    color: black;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin-top: 1px;
}

h1 {
    color: black;
}

label {
    font-weight: bold;
}

.carousel-item img {
    max-width: 650px; 
    max-height: 300px; 
    width: auto; 
    object-fit: cover;
    border-radius: 8px; 
}

/* .carousel-container {
    border: 2px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    background-color: #f8f9fa;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
} */

.carousel-title {
    font-family: 'Arial', sans-serif;
    font-size: 35px;
    color: #333;
    margin-bottom: 10px;
}

.carousel-inner img {
    border-radius: 10px;
}

.carousel-caption {
    background-color: rgba(0, 0, 0, 0.6);
    padding: 15px;
    border-radius: 5px;
}

.carousel-caption h5 {
    font-size: 20px;
    color: #fff;
}

.carousel-caption p {
    font-size: 14px;
    color: #ddd;
}

.carousel-caption .btn {
    margin-top: 10px;
    justify-content: center;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: #333;
    border-radius: 8px;
    padding: 15px;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: #555;
}

.carousel-indicators li {
    background-color: #666;
}

.carousel-indicators .active {
    background-color: #ffcc00;
}

.form-container-img {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);  
    border-radius: 15px;
    background-color: #4c79f5;
    color: whitesmoke;
    padding: 2px;
}

#img_actividad {
    background-color: #333;
    border-radius: 15px;
}

@media (max-width: 768px) {
        .carousel-caption {
            position: static;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 5px;
        }

        .carousel-caption h5,
        .carousel-caption p {
            font-size: 14px;
        }

        .carousel-caption .btn {
            font-size: 15px;
            padding: 5px 10px;
        }

        .carousel-caption form,
        .carousel-caption button {
            display: block;
            width: 100%;
            margin-top: 10px;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 15px;
        }
        .carousel-caption {
            font-size: 50px;
            padding: 33px;
        }

        .carousel-caption .btn {
            font-size: 15px;
            padding: 5px 8px;
        }
    }
</style>

<div id="content">
<div class="container">
        <h1 class="text-center">Crear Nueva Actividad o Novedad</h1>
        
        <?php if (isset($_GET['message'])): ?>
            <div class='alert alert-<?php echo $_GET['alert_type']; ?>'><?php echo $_GET['message']; ?></div>
        <?php endif; ?>

        <form method="POST" action="crear_actividad.php" enctype="multipart/form-data" class="">

        <div class="row">
            <div class="col-md-4">
            <div class="form-group">
                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" class="form-control"  maxlength="30" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="descripcion">Descripcion:</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control"  maxlength="70" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="actividad">Actividad</option>
                    <option value="novedad">Novedad</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="img_actividad">Imagen:</label>
                <div class="form-container-img">
                <label for="img_actividad">Solo se permiten los formatos jpg/jpeg/png</label>
                <input type="file" id="img_actividad" name="img_actividad" class="form-control-file" required>
                </div>
            </div>
        </div>
        </div>
         <button type="submit" class="btn btn-dark btn-block">Guardar</button>
        </form>
    </div>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="carousel-container">
                    <center><h2 class="carousel-title">¡Actividades!</h2></center>
                    <div id="carouselExampleIndicators1" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php foreach ($actividades as $index => $actividad): ?>
                                <li data-target="#carouselExampleIndicators1" data-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active"' : ''; ?>></li>
                            <?php endforeach; ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php foreach ($actividades as $index => $actividad): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img class="d-block w-100 img-fluid" src="<?php echo $actividad['img_actividad']; ?>" alt="<?php echo $actividad['titulo']; ?>">
                                    <div class="carousel-caption">
                                        <h5><?php echo $actividad['titulo']; ?></h5>
                                        <p><?php echo $actividad['descripcion']; ?></p>
                                        <button class="btn btn-warning" onclick="editActivity('<?php echo $actividad['id']; ?>', '<?php echo $actividad['titulo']; ?>', '<?php echo $actividad['descripcion']; ?>', 'actividad')">Editar</button>
                                        <form onsubmit="confirmDelete(event, '<?php echo $actividad['id']; ?>', 'actividad')" class="d-inline">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators1" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators1" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-3">
                <div class="carousel-container">
                    <center><h2 class="carousel-title">¡Novedades!</h2></center>
                    <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php foreach ($novedades as $index => $novedad): ?>
                                <li data-target="#carouselExampleIndicators2" data-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active"' : ''; ?>></li>
                            <?php endforeach; ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php foreach ($novedades as $index => $novedad): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img class="d-block w-100 img-fluid" src="<?php echo $novedad['img_novedad']; ?>" alt="<?php echo $novedad['titulo']; ?>">
                                    <div class="carousel-caption">
                                        <h5><?php echo $novedad['titulo']; ?></h5>
                                        <p><?php echo $novedad['descripcion']; ?></p>
                                        <button class="btn btn-warning" onclick="editActivity('<?php echo $novedad['novedad_id']; ?>', '<?php echo $novedad['titulo']; ?>', '<?php echo $novedad['descripcion']; ?>', 'novedad')">Editar</button>
                                        <form onsubmit="confirmDelete(event, '<?php echo $novedad['novedad_id']; ?>', 'novedad')" class="d-inline">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        function editActivity(id, titulo, descripcion, tipo) {
            document.getElementById('titulo').value = titulo;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('tipo').value = tipo;
            document.getElementById('img_actividad').removeAttribute('required');
            document.querySelector('form').action = 'editar_actividad.php';
            if (!document.getElementById('id')) {
                var idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.id = 'id';
                idInput.name = 'id';
                idInput.value = id;
                document.querySelector('form').appendChild(idInput);
            } else {
                document.getElementById('id').value = id;
            }
        }
        function confirmDelete(event, id, tipo) {
        event.preventDefault(); 

        Swal.fire({
            title: '¿Estas seguro?',
            text: "No podras revertir esto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('eliminar_actividad.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        id: id,
                        tipo: tipo
                    })
                })
                .then(response => response.text())
                .then(data => {
                    Swal.fire(
                        'Eliminado!',
                        'Se elimino correctamente',
                        'success'
                    ).then(() => {
                        location.reload(); 
                    });
                })
                .catch(error => {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al intentar eliminar la entrada.',
                        'error'
                    );
                });
            }
        });
    }
    </script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
