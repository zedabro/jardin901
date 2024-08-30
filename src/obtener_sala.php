<?php
include_once("incluides/dbconexion.php");

if (isset($_GET['sala_id'])) {
    $sala_id = intval($_GET['sala_id']); 

    $sql = "SELECT sala_id, titulo, descripcion, img_sala FROM salas WHERE sala_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $sala_id);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $sala = $result->fetch_assoc();
            echo json_encode($sala);
        } else {
            echo json_encode(['error' => 'No se encontró la sala con el ID proporcionado.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'ID de la sala no proporcionado.']);
}

$conn->close();
?>
