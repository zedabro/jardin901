<?php
include_once("incluides/dbconexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];

    $table = $tipo === 'actividad' ? 'actividades' : 'novedades';
    $column_img = $tipo === 'actividad' ? 'img_actividad' : 'img_novedad';
    $column_id = $tipo === 'actividad' ? 'id' : 'novedad_id';

    if (isset($_FILES['img_actividad']) && $_FILES['img_actividad']['error'] === UPLOAD_ERR_OK) {
        $img_file = $_FILES['img_actividad'];
        $target_dir = $tipo === 'actividad' ? "uploads/actividades/" : "uploads/novedades/";
        $target_file = $target_dir . uniqid() . "_" . basename($img_file['name']);

        if (move_uploaded_file($img_file['tmp_name'], $target_file)) {
            $sql = "UPDATE $table SET titulo = ?, descripcion = ?, $column_img = ? WHERE $column_id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssi", $titulo, $descripcion, $target_file, $id);

                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>" . ucfirst($tipo) . " actualizada con éxito.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Error al actualizar la " . $tipo . ": " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                $message = "<div class='alert alert-danger'>Error al preparar la consulta: " . $conn->error . "</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Error al cargar la imagen de la " . $tipo . ".</div>";
        }
    } else {
        $sql = "UPDATE $table SET titulo = ?, descripcion = ? WHERE $column_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssi", $titulo, $descripcion, $id);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>" . ucfirst($tipo) . " actualizada con exito.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error al actualizar la " . $tipo . ": " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            $message = "<div class='alert alert-danger'>Error al preparar la consulta: " . $conn->error . "</div>";
        }
    }

    $conn->close();
    header("Location: crear_actividad.php?message=" . urlencode($message) . "&alert_type=success");
    exit;
}
?>
