<?php
include_once("incluides/dbconexion.php");

if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']); 

    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Usuario eliminado con exito.</div>';
        } else {
            echo '<div class="alert alert-danger">Error al eliminar usuario: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    } else {
        echo '<div class="alert alert-danger">Error al preparar la consulta: ' . $conn->error . '</div>';
    }
}

$conn->close();

header("Location: usuarios.php");
exit;
?>