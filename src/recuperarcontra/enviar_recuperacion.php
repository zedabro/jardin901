<?php

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php'; 


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = 'bvz7k1ksxz11rnbj0lj8-mysql.services.clever-cloud.com';
    $db_user = 'uhtj264qooomsf9c';
    $db_pass = 'npnnZIsTN3dTbpbujEWD';
    $db_name = 'bvz7k1ksxz11rnbj0lj8';
    $email = $_POST['email'];

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $token = bin2hex(random_bytes(4));

    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour')); 
    $stmt = $conn->prepare("INSERT INTO password_reset (email, token, token_expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $token, $tokenExpiry);
    $stmt->execute();
    $stmt->close();

    
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'leaguenet7@gmail.com'; 
    $mail->Password = 'kvns qlot jbvp yivd';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; 
    

    $mail->setFrom('leaguenet7@gmail.com', 'LeagueNET       ');
    $mail->addAddress($email);

    
    $mail->Subject = 'Recuperacion de clave';


    $resetLink = 'http://localhost/adminjardin/src/recuperarcontra/cambiar_contra.php?token=' . $token;


$mail->Body = 'Para restablecer tu clave, haz clic en el siguiente enlace: ' . $resetLink . "\n\n";
$mail->Body .= 'y pon este codigo: ' . $token;

  
    if ($mail->send()) {
        header('Location:  formulario.php');
        exit;
    } else {
        echo 'El correo no se pudo enviar. Error: ' . $mail->ErrorInfo;
    }

  

    $conn->close();
}
?>
