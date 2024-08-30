<?php
include_once("../incluides/dashboard.php");
include_once("incluides/dbconexion.php");

$user_id = $_SESSION['user_id'] ?? null;
$jerarquia_id = $_SESSION['jerarquia_id'] ?? null;

$message = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $archivo = $_FILES['archivo'];

    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png']; 
    $max_size = 5 * 1024 * 1024; 

    if (!in_array($archivo['type'], $allowed_types)) {
        $message = '<div class="alert alert-danger">Tipo de archivo no permitido.</div>';
    }

    if ($archivo['size'] > $max_size) {
        $message = '<div class="alert alert-danger">El archivo es demasiado grande.</div>';
    }

    $target_dir = "../uploads/contenido_pedagogico/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_name = uniqid() . "_" . basename($archivo['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($archivo['tmp_name'], $target_file)) {
        $sql = "INSERT INTO contenido_pedagogico (titulo, descripcion, archivo, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $titulo, $descripcion, $file_name);

        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">El contenido pedagógico se ha subido correctamente.</div>';
        } else {
            $message = '<div class="alert alert-danger">Error al guardar los datos: ' . $conn->error . '</div>';
        }

        $stmt->close();
    } else {
        $message = '<div class="alert alert-danger">Error al subir el archivo.</div>';
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

    <title>Subir Contenido Pedagógico</title>
    <style>

        /* Contenedor principal con el margen izquierdo para el menú lateral */
        .main-container {
            margin-left: 250px; /* Ajusta este valor según el ancho de tu menú lateral */
            padding: 20px;
            flex: 1;
        }

        /* Estilo del formulario */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .form-group input[type="file"] {
            padding: 10px 0;
        }

        .form-group button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="content">
        <div class="form-container">
            <h2>Subir Contenido Pedagogico</h2>
            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
   
                <div class="form-group">
                    <label for="titulo">Titulo del contenido:</label>
                    <input type="text" name="titulo" id="titulo" maxlength="30" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripcion:</label>
                    <textarea name="descripcion" id="descripcion" maxlength="140" required></textarea>
                </div>

                <div class="form-group">
                    <label for="archivo">Seleccionar archivo:</label>
                    <input type="file" name="archivo" id="archivo" required>
                </div>

                <div class="form-group">
                    <button type="submit">Subir Contenido</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
