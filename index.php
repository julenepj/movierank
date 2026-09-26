<?php
// Iniciar sesión
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/css/output.css" rel="stylesheet">
    <script src="./assets/js/loginValidation.js"></script>
    <title>Login - MovieRank</title>
</head>

<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm">
        <!-- Mensaje de error del cliente (se muestra mediante JS) -->
        <div id="clientError" class="bg-red-500 text-white p-3 rounded mb-4" style="display: none;"></div>
        <!-- Mensaje de error -->
        <?php if (isset($_SESSION["error"])): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form class="bg-slate-800 border border-gray-700 rounded-md shadow-md px-8 pt-6 pb-8"
            id="loginForm" action="/includes/user/login.php" method="post">
            <h2 class="text-2xl font-bold text-center mb-4">Iniciar sesión</h2>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="user">Usuario</label>
                <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400"
                    type="text" name="user" placeholder="Tu usuario">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2" for="pass">Contraseña</label>
                <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400"
                    type="password" name="pass" placeholder="************">
            </div>

            <div class="flex items-center justify-between">
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    type="submit">Iniciar sesión</button>
            </div>

            <div class="text-center mt-4">
                <p>¿No tienes una cuenta?
                    <a class="text-indigo-400 hover:underline" href="/includes/user/registro.php">Regístrate aquí</a>
                </p>
            </div>
        </form>
    </div>

    <?php
    // Limpiar la variable de sesión de error después de mostrarla
    if (isset($_SESSION["error"])) {
        unset($_SESSION["error"]);
    }
    ?>
</body>

</html>