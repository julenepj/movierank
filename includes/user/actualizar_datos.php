<?php
// Iniciar sesión y verificar autenticación
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/css/output.css" rel="stylesheet">
    <title>Actualizar Datos - MovieRank</title>
</head>

<body class="bg-slate-900 text-white">
    <!-- Navbar -->
    <?php include('../../components/navbar.php'); ?>

    <!-- Contenido Principal -->
    <main class="ml-72 p-8">
        <div class="max-w-7xl mx-auto">
            <nav class="mb-6 flex justify-between">
                <h1 class="text-3xl font-bold">Actualizar Información Personal</h1>
            </nav>
            <hr class="my-4 border-gray-600">

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    Datos actualizados correctamente.
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    Error: <?php echo htmlspecialchars($_GET['error']);

                            ?>

                </div>
            <?php endif; ?>

            <!-- Formulario de actualización -->
            <form action="update_user_data.php" method="post" class="bg-slate-800 rounded-lg p-6 border border-gray-700">
                <div class="mb-6">
                    <label class="block text-white text-sm font-medium mb-2">Nombre completo</label>
                    <input type="text" name="new_username"
                        class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300">
                </div>

                <div class="mb-6">
                    <label class="block text-white text-sm font-medium mb-2">Correo electrónico</label>
                    <input type="email" name="email"
                        class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300">
                </div>

                <div class="mb-6">
                    <label class="block text-white text-sm font-medium mb-2">Contraseña antigua</label>
                    <input type="password" name="oldpass" class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300">
                </div>

                <div class="mb-6">
                    <label class="block text-white text-sm font-medium mb-2">Contraseña nueva</label>
                    <input type="password" name="newpass"
                        class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300">
                </div>

               

                <div class="flex justify-end">
                    <input
                        class="cursor-pointer bg-fuchsia-500 hover:bg-fuchsia-600 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit" value="Actualizar">
                </div>
            </form>
        </div>
    </main>
</body>

</html>