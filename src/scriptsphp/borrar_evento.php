<?php
include_once("../incluides/dbconexion.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $id = $conn->real_escape_string($id);

    $sql = "DELETE FROM calendario WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "El evento ha sido borrado exitosamente.";
    } else {
        echo "Error " . $conn->error;
    }
} 
$conn->close();
?>