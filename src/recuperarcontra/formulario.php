<!DOCTYPE html>
<html lang="es">
<head>
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>

:root {
    
    --primary-bg-color: #f4f4f9; 
    --secondary-bg-color: #ffffff; 
    --highlight-color: blueviolet;
    --text-color: #333333; 
    --link-color: blueviolet; 
    --link-hover-color: #e1124c; 
    --box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    --border-radius: 10px;
    --input-border-color: #ced4da;
}

body {
    background-color: var(--primary-bg-color);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background-color: var(--secondary-bg-color);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    text-align: center;
    padding: 20px;
    width: 90%;
    max-width: 500px;
}

.login-container h2,
.login-container label,
.login-container p {
    color: var(--text-color);
}

.login-container input[type="email"],
.login-container input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid var(--input-border-color);
    border-radius: 5px;
    box-sizing: border-box;
}

.login-container input[type="submit"],
.login-container button {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    background-color: var(--highlight-color);
    color: var(--secondary-bg-color);
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.login-container a {
    color: var(--link-color);
}

.login-container a:hover {
    color: var(--link-hover-color);
}

@media (max-width: 768px) {
    .login-container {
        padding: 10px;
    }

    .login-container h2 {
        font-size: 1.5rem;
    }

    .login-container input[type="email"],
    .login-container input[type="password"] {
        padding: 5px;
    }

    .login-container input[type="submit"] {
        padding: 5px;
    }
}

.input {
    height: 40px;
    padding: 10px;
    border: 2px solid var(--input-border-color);
    border-radius: 5px;
    box-shadow: 3px 3px 2px rgba(0, 0, 0, 0.1);
}

.input:focus {
    color: var(--text-color);
    outline-color: var(--highlight-color);
    box-shadow: -3px -3px 15px rgba(138, 43, 226, 0.5);
    transition: .1s;
}

p {
    margin: 10px 0px;
}

button {
    background-color: var(--highlight-color);
    color: var(--secondary-bg-color);
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>
</head>
<body>
<div class="login-container">
                <form action="enviar_recuperacion.php" method="POST" onsubmit="return showConfirmation()">
                    <div class="form-group">
                     <label for="email">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <input type="submit" value="recuperar" class="input">
                        </form>
                        <button type="button" class="input" onclick="goBack()">Volver</button>
                        <div id="confirmation" style="display: none;">Tu código de recuperación fue enviado.</div>
                    </div>
                </div>
            </div>


    <script>
        function showConfirmation() {
            var form = document.querySelector('form');
            form.style.display = 'none';

            var confirmation = document.getElementById('confirmation');
            confirmation.style.display = 'block';

            return true;
        }

        function goBack() {

            history.back();
        }
    </script>        
    </script>
</body>
</html>
