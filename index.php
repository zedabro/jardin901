<?php
session_start();

$servername = "bvz7k1ksxz11rnbj0lj8-mysql.services.clever-cloud.com";
$username = "uhtj264qooomsf9c";
$password = "npnnZIsTN3dTbpbujEWD";
$database = "bvz7k1ksxz11rnbj0lj8";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT id_usuario, documento, contraseña FROM usuarios WHERE documento = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            if (password_verify($contraseña, $usuario['contraseña'])) {
                $_SESSION['user_id'] = $usuario['id_usuario']; 
                $_SESSION['documento'] = $usuario['documento'];

                header("Location: src/index.php");
                exit();
            } else {
                $error_message = "Documento o contraseña incorrectos.";
            }
        } else {
            $error_message = "Documento o contraseña incorrectos.";
        }

        $stmt->close();
    } else {
        $error_message = "Error al preparar la consulta: " . $conn->error;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
:root {
    
    --primary-bg-color: #f4f4f9; 
    --secondary-bg-color: #ffffff; 
    --highlight-color: blueviolet;
    --text-color: #333333; 
    --link-color: blueviolet; 
    --link-hover-color: rgb(192, 150, 231);
    --box-shadow: 0 0 10px rgb(192, 150, 231);
    --border-radius: 10px;
    --input-border-color: rgb(192, 150, 231);
}

        body {
            background-color: var(--primary-bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: var(--secondary-bg-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
            padding: 20px;
            width: 90%;
            max-width: 400px;
        }

        .login-container h2 {
            color: var(--text-color);
        }

        .login-container .form-control {
            border: 1px solid var(--input-border-color);
            border-radius: 5px;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .login-container .form-control:focus {
            border-color: var(--highlight-color);
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .login-container button {
            background-color: var(--highlight-color);
            color: var(--secondary-bg-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 10px;
            width: 100%;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container a {
            color: var(--link-color);
        }

        .login-container a:hover {
            color: var(--link-hover-color);
        }

        .alert {
            margin-bottom: 20px;
        }

        button {
    background-color: var(--highlight-color);
    color: var(--secondary-bg-color);
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

        @media (max-width: 768px) {
            .login-container {
                padding: 15px;
                width: 95%;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Iniciar Sesion</h2>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <form id="loginForm" method="POST" action="index.php">
        <div class="form-group">
            <label for="documento">Documento:</label>
            <input type="text" class="form-control" id="documento" name="documento" required>
        </div>
        <div class="form-group">
            <label for="contraseña">Contraseña:</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
        </div>
        <p><a href="src/recuperarcontra/formulario.php">¿Olvidaste tu contraseña?</a></p>
        <button type="submit" class="btn">Ingresar</button>
    </form>
</div>
</body>
</html>
