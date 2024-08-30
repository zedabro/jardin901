<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $token = $_POST['token'];
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $confirmarContrasena = $_POST['confirmar_contrasena'];

    $db_host = 'bvz7k1ksxz11rnbj0lj8-mysql.services.clever-cloud.com';
    $db_user = 'uhtj264qooomsf9c';
    $db_pass = 'npnnZIsTN3dTbpbujEWD';
    $db_name = 'bvz7k1ksxz11rnbj0lj8';
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT email FROM password_reset WHERE token = ? AND token_expiry >= NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($email);
        $stmt->fetch();
       
        if ($nuevaContrasena === $confirmarContrasena) {
            $hashedPassword = password_hash($nuevaContrasena, PASSWORD_BCRYPT);
            
            $stmtUpdate = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE correo = ?");
            if ($stmtUpdate === false) {
                echo "Error al preparar la consulta UPDATE: " . $conn->error;
            } else {
                $stmtUpdate->bind_param("ss", $hashedPassword, $email);
                $stmtUpdate->execute();
                
                $stmtDelete = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
                $stmtDelete->bind_param("s", $token);
                $stmtDelete->execute();
                
                echo "Contraseña actualizada correctamente. Ahora puedes iniciar sesión con tu nueva contraseña.";
            }
        } else {
            echo "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        }
    } else {
        echo "Token no válido. Por favor, verifica el token e inténtalo de nuevo.";
    }
    
    header("refresh:3;url=../index.php");
    $conn->close();
}
?>
