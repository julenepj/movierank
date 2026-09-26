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
    <title>Página Principal - Notflix</title>
</head>

<body class="bg-slate-900 text-white">
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Contenido Principal -->
    <main class="ml-72 p-8">
        <div class="max-w-7xl mx-auto">
            <nav class="mb-6 flex justify-between">
                <h1 class="text-3xl font-bold">Bienvenido a MovieRank</h1>
                <ul class="flex justify-end bg-slate-900 p-2 ml-20">
                    <li class="mr-6">
                        <a class="text-white bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2 rounded" href="../includes/user/cerrar.php">Desconectar</a>
                    </li>
                </ul>
            </nav>
            <hr class="my-4 border-gray-600">

            <?php include '../pages/peliculasFAV.php'; ?>
          
           
        </div>
    </main>
</body>

</html>