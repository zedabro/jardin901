<?php
include_once("../incluides/dashboard.php");
include_once("incluides/dbconexion.php");

// Consulta para obtener los contenidos pedagógicos
$sql = "SELECT id, titulo, descripcion, archivo, created_at FROM contenido_pedagogico";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenido Pedagógico</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .main-container {
            margin-left: 65px;
            padding: 20px;
            flex: 1;
        }
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            background-color: #f2f2f2;
        }
        button {
            margin: 2px 0px;
        }
        @media (max-width: 768px) {
    .main-container {
        margin-left: 0px;

    }
    .creacion {
        display: none;
    }
}
@media (max-width: 576px) {
    .main-container {
        margin-left: 0px;
    }
    .creacion {
        display: none;
    }
}
    </style>
</head>
<body>
    <div class="main-container">
        <div class="table-container">
            <h2>Contenido Pedagogico</h2>
            <table id="contenidoTable" class="display">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                        <th class="creacion">Fecha de subida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['titulo']; ?></td>
                                <td><?php echo $row['descripcion']; ?></td>
                                <td><a href="../uploads/contenido_pedagogico/<?php echo $row['archivo']; ?>" target="_blank" class="btn btn-primary btn-sm">Abrir</a>
                                <?php if ($jerarquia_id == 1): ?>
                    <button class="btn btn-danger btn-sm btn-borrar" data-id="<?php echo $row['id']; ?>">Borrar</button>
                <?php endif; ?>
                            </td>
                            <td class="creacion"><?php echo $row['created_at']; ?>
                            </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay contenido pedagógico disponible.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contenidoTable').DataTable({
                "language": {
                    url: '//cdn.datatables.net/plug-ins/2.1.2/i18n/es-AR.json',
                },
                "searching": true, 
                "paging": true, 
                "ordering": true 
            });

            $('.btn-borrar').on('click', function() {
            var id = $(this).data('id');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'scriptsphp/eliminar_contenido.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            Swal.fire(
                                '¡Eliminado!',
                                'El contenido ha sido eliminado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error',
                                'Hubo un problema al eliminar el contenido. Intenta nuevamente.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        });
    </script>
</body>
</html>
