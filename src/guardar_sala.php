<?php
include_once("incluides/dbconexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sala_id = intval($_POST['sala_id']);
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Validar que los campos de texto no contengan etiquetas HTML peligrosas
    $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');

    if (isset($_FILES['img_sala']) && $_FILES['img_sala']['error'] === UPLOAD_ERR_OK) {
        $img_sala = $_FILES['img_sala'];

        // Validar que el archivo subido sea una imagen
        $check = getimagesize($img_sala['tmp_name']);
        if ($check === false) {
            echo json_encode(['error' => 'El archivo cargado no es una imagen.']);
            exit;
        }

        $target_dir = "uploads/salas/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . uniqid() . "_" . basename($img_sala['name']);
        if (move_uploaded_file($img_sala['tmp_name'], $target_file)) {
            $sql = "UPDATE salas SET titulo = ?, descripcion = ?, img_sala = ? WHERE sala_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssi", $titulo, $descripcion, $target_file, $sala_id);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Cambios guardados con éxito']);
                } else {
                    echo json_encode(['error' => 'Error al guardar los cambios: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
            }
        } else {
            echo json_encode(['error' => 'Error al cargar la imagen de la sala.']);
        }
    } else {
        $sql = "UPDATE salas SET titulo = ?, descripcion = ? WHERE sala_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssi", $titulo, $descripcion, $sala_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cambios guardados con éxito']);
            } else {
                echo json_encode(['error' => 'Error al guardar los cambios: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
        }
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no permitido.']);
}

$conn->close();
?>
