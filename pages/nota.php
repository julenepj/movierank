<?php

// Iniciar sesión y verificar autenticación
session_start();

// Incluir archivo API
include('../api/reviewsAPI.php');
// Crear instancia de la API
$api = new ReviewsAPI('http://localhost:8080');

// Verificar si hay un usuario en sesión
if (!isset($_SESSION['username'])) {
    echo '<div class="bg-red-500 text-white p-4 rounded mb-6">Debes iniciar sesión para gestionar reviews.</div>';
    exit;
}

$userId = $_SESSION['username'];
$successMsg = '';
$errorMsg = '';

// Eliminación de review
if (isset($_POST['delete_review']) && isset($_POST['review_id'])) {
    try {
        $api->deleteReview($_POST['review_id']);
        $successMsg = "Review eliminada correctamente";
    } catch (Exception $e) {
        $errorMsg = "Error al eliminar la review: " . $e->getMessage();
    }
}

// Creación de review
if (isset($_POST['add_review'])) {
    try {
        $reviewData = [
            'user' => $userId,
            'title' => $_POST['titulo'],
            'rating' => floatval($_POST['rating']),
            'review' => $_POST['review'],
            'reportada' => isset($_POST['reportada']) ? intval($_POST['reportada']) : 0,
            'spoiler' => isset($_POST['spoiler']) ? intval($_POST['spoiler']) : 0
        ];

        $api->createReview($reviewData);

        $successMsg = "Review creada correctamente";
    } catch (Exception $e) {
        $errorMsg = "Error al crear la review: " . $e->getMessage();
    }
}

// Actualización de review
if (isset($_POST['update_review'])) {
    try {
        $reviewId = $_POST['review_id'];
        $reviewData = [
            'user'      => $userId, // Incluye el usuario si la API lo requiere
            'title'     => $_POST['titulo'],
            'rating'    => floatval($_POST['rating']),
            'review'    => $_POST['review'],
            'reportada' => isset($_POST['reportada']) ? intval($_POST['reportada']) : 0,
            'spoiler'   => isset($_POST['spoiler']) ? intval($_POST['spoiler']) : 0  
        ];

        $api->updateReview($reviewId, $reviewData);
        
        // Redirigir para limpiar el formulario y eliminar parámetros GET/POST
        header("Location: nota.php");
        exit;
    } catch (Exception $e) {
        $errorMsg = "Error al actualizar la review: " . $e->getMessage();
    }
}


// Eliminaciones de reviews
if (isset($_POST['delete_all'])) {
    try {
        $api->removeAllReviews($userId);
        $successMsg = "Todas las reviews han sido eliminadas correctamente";
    } catch (Exception $e) {
        $errorMsg = "Error al eliminar las reviews: " . $e->getMessage();
    }
}

if (isset($_POST['delete_bad'])) {
    try {
        $api->removeBadMovieReviews($userId);
        $successMsg = "Todas las reviews malas han sido eliminadas correctamente";
    } catch (Exception $e) {
        $errorMsg = "Error al eliminar las reviews malas: " . $e->getMessage();
    }
}

if (isset($_POST['delete_lowupvotes']) && isset($_POST['upvote_threshold'])) {
    try {
        $threshold = intval($_POST['upvote_threshold']);
        $api->removeLowUpvotedReviews($userId, $threshold);
        $successMsg = "Todas las reviews con menos de " . $threshold . " upvotes han sido eliminadas";
    } catch (Exception $e) {
        $errorMsg = "Error al eliminar reviews: " . $e->getMessage();
    }
}

// Obtener reviews favoritas 
try {
    $reviewsResponse = $api->getReviewsByUser($userId);

    if ($reviewsResponse['status'] >= 400) {
        throw new Exception($reviewsResponse['data']);
    }

    $reviews = is_array($reviewsResponse['data']) ? $reviewsResponse['data'] : [];
} catch (Exception $e) {
    $errorMsg = "Error al cargar las reviews: " . $e->getMessage();
    $reviews = [];
}

// Obtener estadísticas del usuario
try {
    $statsResponse = $api->getStatsByUser($userId);

    if ($statsResponse['status'] >= 400) {
        throw new Exception($statsResponse['data']);
    }

    $stats = is_array($statsResponse['data']) ? $statsResponse['data'] : [];
} catch (Exception $e) {
    $errorMsg = "Error al cargar estadísticas: " . $e->getMessage();
    $stats = [];
}

// Procesar solicitud de edición de review
$editReview = null;
if (isset($_GET['edit'])) {
    try {
        $reviewId = (int)$_GET['edit'];
        $editReview = $api->getReviewById($reviewId) ?? [];
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/css/output.css" rel="stylesheet">
    <title>Gestión Reviews - MovieRank</title>
</head>

<body class="bg-slate-900 text-white">
    <!-- Navbar -->
    <?php include('../components/navbar.php'); ?>

    <!-- Contenido Principal -->
    <main class="ml-72 p-8">
        <div class="max-w-7xl mx-auto">
            <nav class="mb-6 flex justify-between">
                <h1 class="text-3xl font-bold">Añade tu review</h1>
                <ul class="flex justify-end bg-slate-900 p-2 ml-20">
                    <li class="mr-6">
                        <a class="text-white bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2 rounded" href="../includes/user/cerrar.php">Desconectar</a>
                    </li>
                </ul>
            </nav>
            <hr class="my-4 border-gray-600">

            <!-- Mensajes de error/éxito -->
            <!-- En la sección de mensajes, reemplaza con: -->
            <?php if (!empty($errorMsg)): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-6"><?php echo $errorMsg; ?></div>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-6"><?php echo $successMsg; ?></div>
            <?php endif; ?>



            <!-- Formulario para añadir/editar review -->
            <div class="bg-slate-800 p-6 rounded-lg shadow-lg mb-8">
                <h2 class="text-2xl font-semibold mb-4"><?php echo $editReview ? 'Editar Review' : 'Añadir Nueva Review'; ?></h2>

                <form action="" method="POST" class="space-y-4">
                <?php if (!empty($editReview)): ?>
                    <input type="hidden" name="review_id" value="<?= $editReview['id'] ?? '' ?>">
                    <?php endif; ?>



                    <!-- Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-300 mb-1">Título</label>
                        <input type="text" id="titulo" name="titulo"
                            value="<?= htmlspecialchars($editReview['title'] ?? '') ?>"
                            class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300"
                            required>
                    </div>

                    <!-- Puntuación -->
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-300 mb-1">Puntuación (0-10)</label>
                        <input type="number" id="rating" name="rating" min="0" max="10" step="0.1"
                            value="<?= htmlspecialchars($editReview['rating'] ?? '') ?>"
                            class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300"
                            required>
                    </div>

                    <!-- Comentario -->
                    <div>
                        <label for="comentario" class="block text-sm font-medium text-gray-300 mb-1">Comentario</label>
                        <textarea id="comentario" name="review" rows="5"
    class="w-full bg-slate-900 rounded-lg px-4 py-2 border border-gray-700 text-indigo-300"
    required><?= htmlspecialchars($editReview['review'] ?? '') ?></textarea>
                    </div>

                    <!-- Reportada - Siempre visible -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">¿Reportada?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                            <input type="radio" name="reportada" value="1"
                            <?= (isset($editReview['reportada']) && $editReview['reportada'] == 1) ? 'checked' : '' ?>>
                                Sí
                            </label>
                            <label class="flex items-center">
                            <input type="radio" name="reportada" value="0"
                            <?= (!isset($editReview['reportada']) || $editReview['reportada'] == 0) ? 'checked' : '' ?>>
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Spoiler - Siempre visible -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">¿Contiene spoiler?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                            <input type="radio" name="spoiler" value="1"
                            <?= (isset($editReview['spoiler']) && $editReview['spoiler'] == 1) ? 'checked' : '' ?>>
                                Sí
                            </label>
                            <label class="flex items-center">
                            <input type="radio" name="spoiler" value="0"
                            <?= (!isset($editReview['spoiler']) || $editReview['spoiler'] == 0) ? 'checked' : '' ?>>
                                No
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                    <?php if (!empty($editReview)): ?>
                        <button type="submit" name="update_review" class="bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2  rounded text-white">
                                Actualizar Review
                            </button>
                            <a href="nota.php" class="bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2 rounded text-white ml-4">
                                Cancelar
                            </a>
                        <?php else: ?>
                            <button type="submit" name="add_review" class="bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2 rounded text-white">
                                Añadir Review
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Lista de reviews -->
            <div class="bg-slate-800 p-6 mt-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Mis Reviews</h2>


                <?php if (empty($reviews)): ?>
                    <p class="text-gray-400">No hay reviews para mostrar.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 tracking-wider">Título</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 tracking-wider">Puntuación</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 tracking-wider">Upvotes</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 tracking-wider">Comentario</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-300 tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-800 divide-y divide-gray-700">
                                <?php foreach ($reviews as $review): ?>
                                    <?php
                                    // Validate review structure
                                    $validReview = is_array($review) &&
                                        isset(
                                            $review['id'],
                                            $review['title'],
                                            $review['rating'],
                                            $review['review'],
                                            $review['upvotes']
                                        );

                                    if (!$validReview) continue;
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?= htmlspecialchars($review['title'] ?: 'Sin título') ?>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $puntuacion = (float)$review['rating'];
                                            $ratingClass = match (true) {
                                                $puntuacion >= 7 => 'text-green-500',
                                                $puntuacion <= 4.9 => 'text-red-500',
                                                default => 'text-yellow-500'
                                            };
                                            ?>
                                            <span class="<?= $ratingClass ?> font-semibold">
                                                <?= number_format($puntuacion, 1) ?>/10
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?= htmlspecialchars((string)($review['upvotes'] ?? 0)) ?>
                                        </td>

                                        <td class="px-6 py-4">
                                            <?php
                                            $comment = mb_substr(
                                                (string)($review['review'] ?? ''),
                                                0,
                                                100,
                                                'UTF-8'
                                            );
                                            echo htmlspecialchars(
                                                strlen($comment) > 100 ? $comment . '...' : $comment
                                            );
                                            ?>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <?php $reviewId = (int)$review['id']; ?>

                                            <!-- Edit Button -->
                                            <a href="nota.php?edit=<?= $reviewId ?>"
                                                class="text-blue-400 hover:text-blue-300 mr-3"
                                                title="Editar review">
                                                ✏️ Editar
                                            </a>

                                            <!-- Delete Form -->
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="review_id" value="<?= $reviewId ?>">

                                                <button type="submit"
                                                    name="delete_review"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta review permanentemente?')"
                                                    class="text-red-400 hover:text-red-300"
                                                    title="Eliminar review">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>


            <!-- Estadísticas del usuario -->
            <div class="bg-slate-800 p-6 rounded-lg shadow-lg mt-8">
                <h2 class="text-2xl font-semibold mb-4">Mis Estadísticas</h2>

                <?php if ($stats): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-slate-700 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Puntuación Media</p>
                            <p class="text-2xl font-bold mt-1"><?php echo number_format($stats['puntuacion_media'], 1); ?>/10</p>
                        </div>

                        <div class="bg-slate-700 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Total Reviews</p>
                            <p class="text-2xl font-bold mt-1"><?php echo $stats['total_reviews']; ?></p>
                        </div>

                        <div class="bg-slate-700 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Total Upvotes</p>
                            <p class="text-2xl font-bold mt-1"><?php echo $stats['total_upvotes']; ?></p>
                        </div>

                        <div class="bg-slate-700 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Puntuación Máxima</p>
                            <p class="text-2xl font-bold mt-1 text-green-500"><?php echo $stats['puntuacion_maxima']; ?>/10</p>
                            <p class="text-sm text-gray-400"><?php echo $stats['pelicula_max']; ?></p>
                        </div>

                        <div class="bg-slate-700 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Puntuación Mínima</p>
                            <p class="text-2xl font-bold mt-1 text-red-500"><?php echo $stats['puntuacion_minima']; ?>/10</p>
                            <p class="text-sm text-gray-400"><?php echo $stats['pelicula_min']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400">No hay suficientes datos para mostrar estadísticas.</p>
                <?php endif; ?>
            </div>

            <!-- Acciones de eliminación masiva -->
            <div class="bg-slate-800 p-6 rounded-lg shadow-lg mt-8">
                <h2 class="text-2xl font-semibold mb-4">Eliminar</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <form method="POST" action="" onsubmit="return confirm('¿Estás seguro de eliminar TODAS tus reviews? Esta acción no se puede deshacer.')">
                        <button type="submit" name="delete_all" class="w-full bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                            Eliminar Todas las Reviews
                        </button>
                    </form>

                    <form method="POST" action="" onsubmit="return confirm('¿Estás seguro de eliminar todas tus reviews con puntuación menor a 4.9?')">
                        <button type="submit" name="delete_bad" class="w-full bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                            Eliminar Reviews < 4.9
                                </button>
                    </form>

                    <form method="POST" action="" class="flex">
                        <input type="number" name="upvote_threshold" min="0" value="5" class="w-16 px-2 py-2 rounded-l bg-slate-700 text-white border border-slate-600">
                        <button type="submit" name="delete_lowupvotes" class="flex-grow bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                            Eliminar con menos upvotes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>