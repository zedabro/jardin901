<?php
include_once("../incluides/dashboard.php");
include_once("incluides/dbconexion.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}


$user_id = $_SESSION['user_id'];

// Obtener el nombre del usuario y la jerarquía
$sql = "SELECT nombre, jerarquia_id FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nombre_usuario, $jerarquia_id);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}

// Verificar si el usuario es un maestro (jerarquía 3)
if ($jerarquia_id != 3) {
    header('Location: ../index.php');
    exit;
}

// Obtener la sala asignada al maestro
$sql = "SELECT salas.sala_id, salas.titulo, salas.descripcion, salas.img_sala 
        FROM salas 
        INNER JOIN salas_maestros ON salas.sala_id = salas_maestros.sala_id 
        WHERE salas_maestros.id_usuario = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Sala Asignada</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="content">
    <h3>Mi Sala Asignada</h3>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 col-sm-6 col-12 mb-4">';
                echo '<div class="card h-100">';
                echo '<img src="' . htmlspecialchars($row['img_sala']) . '" class="card-img-top" alt="Imagen de la sala">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['titulo']) . '</h5>';
                echo '<p class="card-text">' . htmlspecialchars($row['descripcion']) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No tienes ninguna sala asignada.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
