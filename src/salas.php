<?php
include_once("../incluides/dashboard.php");

$servername = "bvz7k1ksxz11rnbj0lj8-mysql.services.clever-cloud.com";
$username = "uhtj264qooomsf9c";
$password = "npnnZIsTN3dTbpbujEWD";
$database = "bvz7k1ksxz11rnbj0lj8";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT sala_id, titulo, descripcion, img_sala, turno_sala FROM salas";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Salas</title>
    <style>
        h1 { color: black; }
        .row {background-color: #fff;}
        body {background-color: #fff;}
        .container {margin-top: 60px;}
        .card { margin: 10px; background-color: #c4d4ab; }
        .card-title { font-size: 1.25rem; font-weight: bold; }
        .card-img-top { width: 100%; height: 200px;     object-fit: cover;        }
        .card-text { margin-bottom: 10px; }
        button { padding: 10px 20px; font-size: 16px; background-color: beige; color: black; border: none; border-radius: 5px; cursor: pointer; margin: 1px 0px; }
        button:hover { background-color: lightgray; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
        .modal-content { background-color: blueviolet; color: white; padding: 20px; border-radius: 5px; width: 80%; max-width: 600px; }
        .modal button { margin-top: 10px; padding: 10px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; }
        input[type="text"] { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid lightgray; }
        @media (max-width: 768px) { .row { margin-left: 0; margin-right: 0; } .card { margin: 0 auto 10px; } }
    </style>
</head>
<body>
<div class="container">
<center><h1>Salas</h1></center>
   <center><a href="crear_sala.php"><button>Crear sala nueva</button></a></center>
   <center><a href="asignarmaestro.php"><button>Asignar Maestro</button></a></center>

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
                echo '<p class="card-text"><strong>Turno: </strong>' . htmlspecialchars($row['turno_sala']) . '</p>'; 
                echo '<button class="btn btn-primary" onclick="abrirModal(' . $row['sala_id'] . ')">Editar</button>';
                echo ' <button class="btn btn-danger" onclick="eliminarSala(' . $row['sala_id'] . ')">Eliminar</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>

    <div id="modal" class="modal">
        <div class="modal-content">
            <h2>Editar Sala</h2>
            <form id="formulario-editar">
                <input type="hidden" id="sala_id" name="sala_id">
                <label for="titulo">Título:</label><br>
                <input type="text" id="titulo" name="titulo" required><br>
                <label for="descripcion">Descripción:</label><br>
                <input type="text" id="descripcion" name="descripcion" required><br>
                <label for="img_sala">Imagen de la Sala:</label><br>
                <input type="file" id="img_sala" name="img_sala"><br>
                <button type="button" onclick="guardarCambios()">Guardar Cambios</button>
                <button type="button" onclick="cerrarModal()">Cerrar</button>
            </form>
        </div>
    </div>

    </div>
    <script>
        function abrirModal(salaId) {
            fetch(`obtener_sala.php?sala_id=${salaId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('sala_id').value = salaId;
                    document.getElementById('titulo').value = data.titulo;
                    document.getElementById('descripcion').value = data.descripcion;
                    document.getElementById('modal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error al obtener datos de la sala:', error);
                });
        }

        function cerrarModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function guardarCambios() {
            const formData = new FormData(document.getElementById('formulario-editar'));

            fetch('guardar_sala.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cambios guardados con éxito');
                        location.reload();
                    } else {
                        alert('Error al guardar los cambios: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al guardar los cambios:', error);
                });
        }

        function eliminarSala(salaId) {
            if (confirm('¿Estás seguro de que deseas eliminar esta sala?')) {
                fetch('scriptsphp/eliminar_sala.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'sala_id': salaId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Sala eliminada con éxito');
                            location.reload();
                        } else {
                            alert('Error al eliminar la sala: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error al eliminar la sala:', error);
                    });
            }
        }
    </script>
</body>

</html>