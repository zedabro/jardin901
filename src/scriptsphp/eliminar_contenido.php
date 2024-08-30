<?php
include_once("../incluides/dbconexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Eliminar el contenido pedagógico de la base de datos
    $sql = "DELETE FROM contenido_pedagogico WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "El contenido ha sido eliminado exitosamente.";
    } else {
        echo "Error al eliminar el contenido: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
