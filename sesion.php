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