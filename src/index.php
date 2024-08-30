<?php
include_once("../incluides/dashboard.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardin901 - Inicio</title>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>
<div id="content">
    <h1>Panel de control</h1>
    <div class="row">
        <?php if ($jerarquia_id == 1): ?>
            <div class="col-md-4">
                <a href="crear_actividad.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-book icon"></i> 
                            <h5 class="card-title">Noticias</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="pedagogico.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-upload icon"></i> 
                            <h5 class="card-title">Cargar Cont.Pedagogico</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="salas.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-door-open icon"></i> 
                            <h5 class="card-title">Salas</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="calendario.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt icon"></i> 
                            <h5 class="card-title">Calendario</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="usuarios.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-users icon"></i> 
                            <h5 class="card-title">Usuarios</h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($jerarquia_id == 2): ?>
            <div class="col-md-4">
                <a href="crear_actividad.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-book icon"></i> 
                            <h5 class="card-title">Noticias</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="pedagogico.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-upload icon"></i> 
                            <h5 class="card-title">Cargar Cont.Pedagogico</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="salas.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-door-open icon"></i> 
                            <h5 class="card-title">Salas</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="calendario.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt icon"></i> 
                            <h5 class="card-title">Calendario</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="usuarios.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-users icon"></i> 
                            <h5 class="card-title">Usuarios</h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($jerarquia_id == 3): ?>
            <div class="col-md-4">
                <a href="vercontenido.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-book icon"></i> 
                            <h5 class="card-title">Contenido Pedagogico</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="salamaestro.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-door-open icon"></i> 
                            <h5 class="card-title">Salas</h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
