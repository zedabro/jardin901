<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['title'];
    $descripcion = $_POST['description'];
    $fecha = $_POST['date'];

    include_once("../incluides/dbconexion.php");

    $sql = "INSERT INTO calendario (fecha_guardada, titulo, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $fecha, $titulo, $descripcion);

        if ($stmt->execute()) {
            $event_id = $stmt->insert_id; // Obtener el último ID insertado
            if ($event_id > 0) { // Verificar que el ID sea válido
                echo json_encode([
                    "status" => "success",
                    "message" => "Evento guardado correctamente.",
                    "event" => [
                        "id" => $event_id,
                        "title" => $titulo,
                        "description" => $descripcion,
                        "date" => $fecha
                    ]
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se pudo obtener el ID del evento insertado."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al guardar el evento: " . $stmt->error
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