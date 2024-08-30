<?php
include_once("incluides/dbconexion.php");
include_once("../incluides/dashboard.php");

$target_dir = "uploads/salas/";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $turno_sala = $_POST['turno_sala'];
    
    if (isset($_FILES['img_sala']) && $_FILES['img_sala']['error'] === UPLOAD_ERR_OK) {
        $img_sala = $_FILES['img_sala'];

        $target_file = $target_dir . uniqid() . "_" . basename($img_sala['name']);

        if (move_uploaded_file($img_sala['tmp_name'], $target_file)) {
            $sql = "INSERT INTO salas (titulo, descripcion, img_sala, turno_sala) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("ssss", $titulo, $descripcion, $target_file, $turno_sala);
                
                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>Sala creada con éxito.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Error al crear la sala: " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                $message = "<div class='alert alert-danger'>Error al preparar la consulta: " . $conn->error . "</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Error al cargar la imagen de la sala.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Error al cargar la imagen de la sala.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Sala</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h1 {
            color: black;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:hover {
            background-color: #23272b;
            border-color: #1d2124;
        }
    </style>
</head>
<body>
    <div id="content">
        <div class="container">
            <h1 class="text-center">Crear Nueva Sala</h1>

            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>

            <form method="POST" action="crear_sala.php" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
                <div class="form-group">
                    <label for="titulo">Nombre de la Sala:</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" maxlength="30" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción de la Sala:</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control"  maxlength="80"required>
                </div>

                <div class="form-group">
                    <label for="turno_sala">Turno de la Sala:</label>
                    <select id="turno_sala" name="turno_sala" class="form-control" required>
                        <option value="mañana">Mañana</option>
                        <option value="tarde">Tarde</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="img_sala">Imagen de la Sala:</label>
                    <input type="file" id="img_sala" name="img_sala" class="form-control-file" required>
                </div>

                <button type="submit" class="btn btn-dark btn-block">Crear Sala</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
