<?php
include_once("incluides/dbconexion.php");

$message = '';

// Consultar actividades, novedades y salas
$sql_actividades = "SELECT actividad_id, titulo FROM actividades";
$result_actividades = $conn->query($sql_actividades);

$sql_novedades = "SELECT novedad_id, titulo FROM novedades";
$result_novedades = $conn->query($sql_novedades);

$sql_salas = "SELECT sala_id, titulo FROM salas";
$result_salas = $conn->query($sql_salas);

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sala_id = isset($_POST['sala_id']) ? $_POST['sala_id'] : null;
    $actividad_id = isset($_POST['actividad_id']) ? $_POST['actividad_id'] : null;
    $novedad_id = isset($_POST['novedad_id']) ? $_POST['novedad_id'] : null;

    if ($sala_id && ($actividad_id || $novedad_id)) {
        if ($actividad_id) {
            $sql_insertar = "INSERT INTO salas_actividades (sala_id, actividad_id) VALUES ($sala_id, $actividad_id)";
            
            if ($conn->query($sql_insertar) === TRUE) {
                $message = '<div class="alert alert-success">Actividad asignada con éxito.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error al asignar la actividad: ' . $conn->error . '</div>';
            }
        }

        if ($novedad_id) {
            $sql_insertar = "INSERT INTO salas_novedades (sala_id, novedad_id) VALUES ($sala_id, $novedad_id)";
            
            if ($conn->query($sql_insertar) === TRUE) {
                $message = '<div class="alert alert-success">Novedad asignada con éxito.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error al asignar la novedad: ' . $conn->error . '</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-danger">Selecciona una sala y una actividad o novedad.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Actividades o Novedades a Sala</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Asignar Actividades o Novedades a una Sala</h1>
        <?php echo $message; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="sala_id">Seleccionar Sala:</label>
                <select name="sala_id" id="sala_id" class="form-control" required>
                    <?php
                    if ($result_salas->num_rows > 0) {
                        while ($row = $result_salas->fetch_assoc()) {
                            echo '<option value="' . $row['sala_id'] . '">' . htmlspecialchars($row['titulo']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="actividad_id">Seleccionar Actividad:</label>
                <select name="actividad_id" id="actividad_id" class="form-control">
                    <option value="">-- Ninguna --</option>
                    <?php
                    if ($result_actividades->num_rows > 0) {
                        while ($row = $result_actividades->fetch_assoc()) {
                            echo '<option value="' . $row['actividad_id'] . '">' . htmlspecialchars($row['titulo']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="novedad_id">Seleccionar Novedad:</label>
                <select name="novedad_id" id="novedad_id" class="form-control">
                    <option value="">-- Ninguna --</option>
                    <?php
                    if ($result_novedades->num_rows > 0) {
                        while ($row = $result_novedades->fetch_assoc()) {
                            echo '<option value="' . $row['novedad_id'] . '">' . htmlspecialchars($row['titulo']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Asignar</button>
        </form>
    </div>
</body>
</html>
