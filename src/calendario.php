<?php
include_once("../incluides/dashboard.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/calendario.css">
    <style>
    #editModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: blueviolet;
    color: white;
    padding: 20px;
    border-radius: 10px;
    z-index: 1000;
    }
    #viewModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: blueviolet; 
    color: white; 
    padding: 20px;
    border-radius: 10px;
    z-index: 1000;
    }
    button {
        margin: 2px 0px;
    }
    #viewModal .modal-content {
    background-color: transparent; 
    color: inherit;
    padding: 0;
    border: none;
    box-shadow: none;
    }
    @media (max-width: 768px) {
        #viewModal {
            width:80%;
    }
    .creacion {
        display: none;
    }
}
@media (max-width: 576px) {

    #viewModal {
            width:80%;
            font-size:20px;
    }
    .creacion {
        display: none;
    }
}
    </style>
</head>
<body>
    <div id="content">
        <h1>Calendario</h1>
        <div id='calendar'></div>
        <br>
        <h5>Fechas cargadas en el calendario:</h5>
        <?php
include_once("incluides/dbconexion.php");

$sql = "SELECT id, titulo, fecha_guardada, descripcion FROM calendario";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
?>
    <div class="table-container">
        <table id="eventTable" class="display">
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Fecha Guardada</th>
                    <th class="creacion">Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($fila = $resultado->fetch_assoc()) {
                ?>
                    <tr id="<?php echo $fila["id"]; ?>">
                        <td><?php echo $fila["titulo"]; ?></td>
                        <td><?php echo $fila["fecha_guardada"]; ?></td>
                        <td class="creacion"><?php echo $fila["descripcion"]; ?></td>
                        <td>
                            <button class="btn btn-primary btn-editar" data-id="<?php echo $fila["id"]; ?>" data-title="<?php echo $fila["titulo"]; ?>" data-date="<?php echo $fila["fecha_guardada"]; ?>" data-description="<?php echo $fila["descripcion"]; ?>">Editar</button>
                            <button class="btn btn-danger btn-borrar" onclick="borrarEvento(<?php echo $fila["id"]; ?>)">Borrar</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "No hay eventos cargados, si cargo un evento y no se visualiza en la lista pruebe refrescando";
}
$conn->close();
?>
    </div>  

    <div id="modal">
        <h2>Agregar Evento</h2>
        <form id="eventForm">
            <div>
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" maxlength="40" required>
            </div>
            <div>
                <label for="description">Descripcion:</label>
                <textarea id="description" name="description" rows="3" maxlength="80"></textarea>
            </div>
            <div>
                <label for="date">Fecha:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <button class="btn btn-primary" type="submit">Guardar</button>
            <button class="btn btn-dark" type="button" onclick="cerrarModal()">Cerrar</button>
        </form>
    </div>

    <div id="editModal">
        <h2>Editar Evento</h2>
        <form id="editEventForm">
            <input type="hidden" id="editId" name="id">
            <div>
                <label for="editTitle">Título:</label>
                <input type="text" id="editTitle" name="title" maxlength="40" required>
            </div>
            <div>
                <label for="editDescription">Descripción:</label>
                <textarea id="editDescription" name="description" rows="3" maxlength="80" required></textarea>
            </div>
            <div>
                <label for="editDate">Fecha:</label>
                <input type="date" id="editDate" name="date" required>
            </div>
            <button class="btn btn-primary" type="submit">Actualizar</button>
            <button class="btn btn-dark" type="button" onclick="cerrarEditModal()">Cerrar</button>
        </form>
    </div>

    <div id="viewModal">
      <div class="modal-content">
        <span onclick="cerrarViewModal()" class="close" style="color: white; cursor: pointer;">&times;</span>
        <h2>Detalles del Evento</h2>
        <p><strong>Nombre evento:</strong> <span id="viewTitle"></span></p>
        <p><strong>Fecha a realizarse:</strong> <span id="viewDate"></span></p>
        <p><strong>Descripcion:</strong> <span id="viewDescription"></span></p>
      </div>
    </div>


    <script>
        var calendar;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'scriptsphp/obtener_eventos.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            successCallback(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            failureCallback(error);
                        }
                    });
                },
                dateClick: function(info) {
                    $('#modal').show();
                    $('#date').val(info.dateStr);
                },
                eventClick: function(info) {
                var event = info.event;
                $('#viewTitle').text(event.title);
                $('#viewDate').text(event.startStr); 
                $('#viewDescription').text(event.extendedProps.description);
                $('#viewModal').show();
                },
                eventContent: function(info) {
                    var eventTitle = info.event.title;
                    var eventElement = document.createElement('div');
                    eventElement.innerText = eventTitle;
                    eventElement.style.backgroundColor = 'green';
                    return { domNodes: [eventElement] };
                }
            });
            calendar.render();

            $(document).on('click', function(event) {
                if ($(event.target).closest('#modal').length === 0) {
                    $('#modal').hide();
                }
            });

            $('#eventForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'scriptsphp/guardar_evento.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            alert(response.message);
                            $('#modal').hide();

                            var newEvent = response.event;
                            var eventHTML = '<li id="' + newEvent.id + '" class="list-group-item">'
                                + '<span><strong>Titulo:</strong> ' + newEvent.title + '</span><br>'
                                + '<span><strong>Fecha Guardada:</strong> ' + newEvent.date + '</span><br>'
                                + '<span><strong>Descripción:</strong> ' + newEvent.description + '</span><br>'
                                + '<button class="btn btn-primary btn-editar" data-id="' + newEvent.id + '" data-title="' + newEvent.title + '" data-date="' + newEvent.date + '" data-description="' + newEvent.description + '">Editar</button>'
                                + '<button class="btn btn-danger btn-borrar" onclick="borrarEvento(' + newEvent.id + ')">Borrar</button>'
                                + '</li>';
                            $('#eventList').append(eventHTML);

                            calendar.addEvent({
                                id: newEvent.id,
                                title: newEvent.title,
                                start: newEvent.date,
                                description: newEvent.description
                            });
                            $('#eventForm')[0].reset();
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error al guardar el evento. Por favor, inténtalo de nuevo.');
                    }
                });
            });

            $(document).on('click', '.btn-editar', function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var date = $(this).data('date');
                var description = $(this).data('description');

                $('#editId').val(id);
                $('#editTitle').val(title);
                $('#editDate').val(date);
                $('#editDescription').val(description);

                $('#editModal').show();
            });

            $('#editEventForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'scriptsphp/actualizar_evento.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            alert(response.message);
                            $('#editModal').hide();

                            var updatedEvent = response.event;

                            var eventHTML = '<span><strong>Título:</strong> ' + updatedEvent.title + '</span><br>'
                                + '<span><strong>Fecha Guardada:</strong> ' + updatedEvent.date + '</span><br>'
                                + '<span><strong>Descripción:</strong> ' + updatedEvent.description + '</span><br>'
                                + '<button class="btn btn-primary btn-editar" data-id="' + updatedEvent.id + '" data-title="' + updatedEvent.title + '" data-date="' + updatedEvent.date + '" data-description="' + updatedEvent.description + '">Editar</button>'
                                + '<button class="btn btn-danger btn-borrar" onclick="borrarEvento(' + updatedEvent.id + ')">Borrar</button>';

                            $('#' + updatedEvent.id).html(eventHTML);

                            var calendarEvent = calendar.getEventById(updatedEvent.id);
                            if (calendarEvent) {
                                calendarEvent.remove();
                            }

                            calendar.addEvent({
                                id: updatedEvent.id,
                                title: updatedEvent.title,
                                start: updatedEvent.date,
                                description: updatedEvent.description
                            });
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error al actualizar el evento. Por favor, inténtalo de nuevo.');
                    }
                });
            });
        });

        function borrarEvento(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'scriptsphp/borrar_evento.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    // Verificar si la eliminación fue exitosa antes de eliminar del DOM
                    if (response.trim() === "El evento ha sido borrado exitosamente.") {
                        // Eliminar el evento del calendario
                        var event = calendar.getEventById(id);
                        if (event) {
                            event.remove();
                        }

                        // Eliminar el evento de la lista (DOM)
                        $("#" + id).remove();
                        
                        // Mostrar notificación de éxito
                        Swal.fire(
                            'Eliminado',
                            'El evento ha sido eliminado.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            'Hubo un problema al eliminar el evento. Por favor, inténtalo de nuevo.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar el evento. Por favor, inténtalo de nuevo.',
                        'error'
                    );
                }
               });
            }
          });
        }
        function cerrarModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function cerrarEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function cerrarViewModal() {
        document.getElementById('viewModal').style.display = 'none';
        }
        $(document).ready(function() {
            $('#eventTable').DataTable({
                "language": {
                    url: '//cdn.datatables.net/plug-ins/2.1.2/i18n/es-AR.json',
                },
                "searching": true, 
                "paging": true, 
                "ordering": true 
            });
    });
    </script>
</body>
</html>
