<?php
require_once('../api/movieAPI.php'); // Asegúrate de que este archivo obtiene correctamente los datos de la API

// Obtener el número de página actual desde la URL, si no existe, establecer en 1
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Definir el número de películas por página (lo establece la API de TMDb por defecto en 20)
$peliculas_por_pagina = 20;

// Obtener el total de páginas desde la API
$total_paginas = isset($data["total_pages"]) ? (int) $data["total_pages"] : 1;

// Limitar el máximo de páginas a 500 (Límite impuesto por TMDb)
$total_paginas = min($total_paginas, 500);

// Obtener las películas de la página actual
$peliculas_pagina = $data["results"];

// Definir el rango de páginas a mostrar
$rango = 4; // Cuántas páginas mostrar antes y después de la actual

// Calcular los límites del rango de paginación
$inicio_rango = max(1, $pagina_actual - $rango);
$fin_rango = min($total_paginas, $pagina_actual + $rango);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/css/output.css" rel="stylesheet">
    <title>Películas Populares</title>
</head>

<body class="bg-slate-900">

    <main>
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-white">Películas Populares</h1>

            <!-- Contenedor Grid con 4 columnas -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($peliculas_pagina as $movie): ?>
                    <div class="bg-gray-800 p-2 rounded-lg shadow-md text-center">
                        <img src="https://image.tmdb.org/t/p/w200<?php echo $movie['poster_path']; ?>" class="w-full h-auto rounded-md">
                        <h2 class="text-sm font-semibold mt-2 truncate"><?php echo $movie["title"]; ?></h2>
                        
                        <?php
                        $rating = $movie["vote_average"];
                        $fullStars = floor($rating / 2);
                        $decimal = ($rating / 2) - $fullStars;
                        $halfStar = $decimal >= 0.5 ? true : false;
                        ?>

                        <p class="text-yellow-400 text-xs">
                            <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                ⭐
                            <?php endfor; ?>
                            <?php if ($halfStar): ?>
                                ½
                            <?php endif; ?>
                            (<?php echo number_format($rating, 1); ?>)
                        </p>
                        
                        <!-- Botón para añadir a favoritos -->
                        <form action="../components/add_favorite.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $movie["id"]; ?>">
                            <input type="hidden" name="title" value="<?php echo $movie["title"]; ?>">
                            <input type="hidden" name="poster_path" value="<?php echo $movie["poster_path"]; ?>">
                            <button type="submit" class="mt-2 bg-fuchsia-600 hover:bg-fuchsia-700 text-white py-1 px-3 rounded text-xs">
                                Añadir a Favoritos
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginación -->
            <div class="mt-6 flex justify-center space-x-2">
                <?php if ($pagina_actual > 1): ?>
                    <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">Anterior</a>
                <?php endif; ?>

                <!-- Primera Página (Siempre Mostrarla) -->
                <?php if ($inicio_rango > 1): ?>
                    <a href="?pagina=1" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">1</a>
                    <?php if ($inicio_rango > 2): ?>
                        <span class="px-4 py-2 text-gray-400">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Páginas en el Rango Calculado -->
                <?php for ($i = $inicio_rango; $i <= $fin_rango; $i++): ?>
                    <?php if ($i == $pagina_actual): ?>
                        <span class="px-4 py-2 bg-fuchsia-600 text-white rounded"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?pagina=<?php echo $i; ?>" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Última Página (Siempre Mostrarla) -->
                <?php if ($fin_rango < $total_paginas): ?>
                    <?php if ($fin_rango < $total_paginas - 1): ?>
                        <span class="px-4 py-2 text-gray-400">...</span>
                    <?php endif; ?>
                    <a href="?pagina=<?php echo $total_paginas; ?>" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600"><?php echo $total_paginas; ?></a>
                <?php endif; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">Siguiente</a>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>

</html>
