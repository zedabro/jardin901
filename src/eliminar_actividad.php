<?php
include_once("../incluides/dashboard.php");
include_once("incluides/dbconexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['tipo'])) {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];

        if ($tipo === 'actividad' || $tipo === 'novedad') {
            $table = $tipo === 'actividad' ? 'actividades' : 'novedades';
            $column = $tipo === 'actividad' ? 'id' : 'novedad_id';

            $sql = "DELETE FROM $table WHERE $column=?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>" . ucfirst($tipo) . " eliminada con éxito.</div>";
                } else {
                    error_log("Error executing statement: " . $stmt->error);
                    $message = "<div class='alert alert-danger'>Error al eliminar la " . $tipo . ".</div>";
                }
                $stmt->close();
            } else {
                error_log("Error preparing statement: " . $conn->error);
                $message = "<div class='alert alert-danger'>Error al preparar la consulta.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Tipo inválido especificado.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>ID o tipo faltante en la solicitud.</div>";
    }
} else {
    $message = "<div class='alert alert-danger'>Solicitud inválida.</div>";
}

$conn->close();

header("Location: crear_actividad.php?message=" . urlencode($message));
exit();
?>
