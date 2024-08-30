<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['title'];
    $descripcion = $_POST['description'];
    $fecha = $_POST['date'];

    include_once("../incluides/dbconexion.php");

    $sql = "UPDATE calendario SET titulo = ?, descripcion = ?, fecha_guardada = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssi", $titulo, $descripcion, $fecha, $id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Evento actualizado correctamente.",
                "event" => [
                    "id" => $id,
                    "title" => $titulo,
                    "description" => $descripcion,
                    "date" => $fecha
                ]
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar el evento: " . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al preparar la consulta: " . $conn->error
        ]);
    }

    $conn->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error: Se esperaba una solicitud POST."
    ]);
}
?>
