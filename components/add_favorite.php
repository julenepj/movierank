<?php
session_start();

include "../database/db.php";

if (!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}

$username = $_SESSION["username"];

// Obtener user_id
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Error: Usuario no encontrado");
}

$user_id = $user["id"];

if (isset($_POST['id'], $_POST['title'], $_POST['poster_path'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $poster_path = $_POST['poster_path'];

    $sql = "SELECT * FROM favoritos WHERE user_id = ? AND movie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $movie_id]);
    $existe = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se recuperaron favoritos
    if (!$existe) {

        $sql = "INSERT INTO favoritos (user_id, movie_id, title, poster_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $movie_id, $title, $poster_path]);
    
    
    
    }
    header("Location: add_favorite.php");
    exit;
}

// Obtener todos los favoritos del usuario para mostrarlos
$sql = "SELECT * FROM favoritos WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*if (!isset($_SESSION["favoritos"])) {
        $_SESSION["favoritos"] = [];
    }

    // Verificar si la película ya está en favoritos
    $existe = false;
    foreach ($_SESSION["favoritos"] as $fav) {
        if ($fav["id"] == $id) {
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $_SESSION["favoritos"][] = [
            "id" => $id,
            "title" => $title,
            "poster_path" => $poster_path
        ];
    } */

// Código para redirigir a la página de favoritos eliminado para evitar código inalcanzable




?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Películas Favoritas - MovieRank</title>
</head>

<body class="bg-slate-900 text-white">
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Contenido Principal -->
    <main class="ml-72 p-8">
        <div class="max-w-7xl mx-auto">
            <nav class="mb-6 flex justify-between">
                <h1 class="text-3xl font-bold">Películas Favoritas</h1>
                <ul class="flex justify-end bg-slate-900 p-2 ml-20">
                    <li class="mr-6">
                        <a class="text-white bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2 rounded" href="../includes/user/cerrar.php">Desconectar</a>
                    </li>
                </ul>
            </nav>
            <hr class="my-4 border-gray-600">

            

            <!-- Grid de Películas Favoritas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (!empty($favoritos)): ?>
                    <?php foreach ($favoritos as $movie): ?>
                        <div class="bg-slate-800 p-4 rounded-lg shadow-lg text-center">
                            <img src="https://image.tmdb.org/t/p/w200<?php echo $movie["poster_path"]; ?>" class="w-full h-auto rounded-md">
                            <h2 class="text-lg font-semibold text-fuchsia-400 mt-2 truncate"><?php echo $movie["title"];?></h2>
                            
                           <!--<form action="../pages/nota.php" method="post">
                                <input type="hidden" name="nota" value="">
                                <button type="submit" class="mt-3 bg-red-500 hover:bg-red-700 text-white py-1 px-4 rounded text-sm">
                                    Poner Nota
                                </button>
                            </form> -- Por si en algun futuro se quiseira añadir una nota a la pelicula-->
                            <form action="remove_favorite.php" method="post"  onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta pelicula de favoritos?');">
                                <input type="hidden" name="id" value="<?php echo $movie["id"]; ?>">
                                <button type="submit" class="mt-3 bg-red-500 hover:bg-red-700 text-white py-1 px-4 rounded text-sm">
                                    Eliminar
                                </button>

                            </form>

                           
                        </div>
                    <?php
                    endforeach; ?>
                <?php else: ?>
                    <p class='text-gray-400 text-center'>No has añadido ninguna película a favoritos.</p>
                <?php endif; ?>
                
            </div>

            <!-- Sección de Estadísticas -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-4">Tus Estadísticas</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <?php

                    if (!isset($_SESSION["username"])) {
                        header("Location: ../../index.php");
                        exit();
                    }

                    $username = $_SESSION["username"];

                    // Obtener el user_id del usuario autenticado
                    $sql = "SELECT id FROM users WHERE username = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$username]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$user) {
                        die("Error: Usuario no encontrado");
                    }

                    $user_id = $user["id"];

                    // 1️⃣ Contar cuántas películas favoritas tiene el usuario
                    $sql = "SELECT COUNT(*) AS total_favoritos FROM favoritos WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$user_id]);
                    $total_favoritos = $stmt->fetch(PDO::FETCH_ASSOC)["total_favoritos"];

                    // 2️⃣ Calcular la valoración promedio (suponiendo que tienes una columna "rating")
                    $sql = "SELECT AVG(rating) AS avg_rating FROM favoritos WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$user_id]);
                    $avg_rating = $stmt->fetch(PDO::FETCH_ASSOC)["avg_rating"] ?? 0;
                    $avg_rating = round($avg_rating, 1); // Redondear a 1 decimal

                    // 3️⃣ Obtener el género más frecuente entre las películas favoritas
                    $sql = "SELECT genre, COUNT(genre) AS count FROM favoritos WHERE user_id = ? GROUP BY genre ORDER BY count DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$user_id]);
                    $favorite_genre = $stmt->fetch(PDO::FETCH_ASSOC)["genre"] ?? "Desconocido";

                    // 4️⃣ Obtener la última película agregada
                    $sql = "SELECT title FROM favoritos WHERE user_id = ? ORDER BY id DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$user_id]);
                    $last_movie = $stmt->fetch(PDO::FETCH_ASSOC)["title"] ?? "Ninguna";
                    ?>

                    <div class="bg-slate-800 p-4 rounded-lg">
                        <h3 class="text-lg text-fuchsia-400">Películas Favoritas</h3>
                        <p class="text-indigo-300 text-2xl"><?php echo $total_favoritos; ?></p>
                    </div>

                    <div class="bg-slate-800 p-4 rounded-lg">
                        <h3 class="text-lg text-fuchsia-400">Valoración Promedio</h3>
                        <p class="text-indigo-300 text-2xl"><?php echo $avg_rating; ?></p>
                    </div>

                    <div class="bg-slate-800 p-4 rounded-lg">
                        <h3 class="text-lg text-fuchsia-400">Género Favorito</h3>
                        <p class="text-indigo-300 text-2xl"><?php echo $favorite_genre; ?></p>
                    </div>

                    <div class="bg-slate-800 p-4 rounded-lg">
                        <h3 class="text-lg text-fuchsia-400">Última Película Agregada</h3>
                        <p class="text-indigo-300 text-sm"><?php echo $last_movie; ?></p>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>

</html>