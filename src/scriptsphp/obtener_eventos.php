<?php
include_once("../incluides/dbconexion.php");

$sql = "SELECT id, titulo, fecha_guardada, descripcion FROM calendario";
$resultado = $conn->query($sql);

$eventos = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $eventos[] = [
            'id' => $fila['id'],
            'title' => $fila['titulo'],
            'start' => $fila['fecha_guardada'],
            'description' => $fila['descripcion']
        ];
    }
}

$conn->close();

echo json_encode($eventos);
?>
