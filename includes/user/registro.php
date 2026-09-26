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
    <title>Registro - MovieRank</title>
</head>

<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm">
        <!-- Mensaje de error -->
        <?php if (isset($_SESSION["error"])): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form class="bg-slate-800 border border-gray-700 rounded-md shadow-md px-8 pt-6 pb-8" 
              action="register.php" method="post">
            <h2 class="text-2xl font-bold text-center mb-4">Registro</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="user">Usuario</label>
                <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400"
                       type="text" name="user" placeholder="Tu usuario" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" for="email">Correo electrónico</label>
                <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400"
                       type="email" name="email" placeholder="Tu email" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2" for="pass">Contraseña</label>
                <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400"
                       type="password" name="pass" placeholder="************" required>
            </div>

            <div class="flex items-center justify-between">
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        type="submit">Registrarse</button>
            </div>

            <div class="text-center mt-4">
                <p>¿Ya tienes una cuenta? 
                    <a class="text-indigo-400 hover:underline" href="/index.php">Inicia sesión aquí</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>
