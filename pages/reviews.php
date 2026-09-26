<?php
// reviews.php
session_start();
include "../api/reviewsAPI.php";

// Verificar autenticación
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['username'];
$error = '';
$success = '';

$api = new ReviewsAPI('http://localhost:8080');

// Obtener review a editar
if (isset($_GET['edit'])) {
    try {
        $reviewId = (int)$_GET['edit'];
        $review = $api->getReviewById($reviewId);
        
       
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_review'])) {
    try {
        $reviewId = (int)$_POST['review_id'];
        
        // Validar datos
        $updatedData = [
            'title' => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING),
            'rating' => filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_FLOAT, [
                'options' => [
                    'min_range' => 0,
                    'max_range' => 10
                ]
            ]),
            'review' => filter_input(INPUT_POST, 'review', FILTER_SANITIZE_STRING),
            'spoiler' => isset($_POST['spoiler']) ? 1 : 0,
            'reportada' => isset($_POST['reportada']) ? 1 : 0
        ];
        
        if (!$updatedData['rating']) {
            throw new Exception("La puntuación debe ser entre 0 y 10");
        }
        
        // Actualizar review
        if ($api->updateReview($reviewId, $updatedData)) {
            $success = "¡Review actualizada correctamente!";
            header("Refresh: 2; URL=reviews.php");
        }
        
    } catch (Exception $e) {
        $error = "Error actualizando review: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/css/output.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>

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
<?php if (isset($review)): ?>
<div class="max-w-2xl mx-auto p-6 bg-slate-800 rounded-lg mt-8">
    <h2 class="text-2xl font-bold text-fuchsia-400 mb-6">Editar Review</h2>
    
    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-800 text-red-200 rounded"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="mb-4 p-3 bg-green-800 text-green-200 rounded"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
        
        <div class="mb-4">
            <label class="block text-gray-300 mb-2">Título</label>
            <input type="text" name="titulo" 
                   value="<?= htmlspecialchars($review['title']) ?>" 
                   class="w-full p-2 bg-slate-700 rounded text-white">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-300 mb-2">Puntuación (0-10)</label>
            <input type="number" name="rating" step="0.1" min="0" max="10"
                   value="<?= htmlspecialchars($review['rating']) ?>" 
                   class="w-full p-2 bg-slate-700 rounded text-white">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-300 mb-2">Review</label>
            <textarea name="review" rows="5"
                      class="w-full p-2 bg-slate-700 rounded text-white"><?= 
                      htmlspecialchars($review['review']) ?></textarea>
        </div>
        
        <div class="mb-4 flex gap-4">
            <label class="flex items-center text-gray-300">
                <input type="checkbox" name="spoiler" 
                       <?= $review['spoiler'] ? 'checked' : '' ?>
                       class="mr-2"> Contiene spoiler
            </label>
            
            <label class="flex items-center text-gray-300">
                <input type="checkbox" name="reportada" 
                       <?= $review['reportada'] ? 'checked' : '' ?>
                       class="mr-2"> Reportada
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" name="update_review"
                    class="bg-fuchsia-600 hover:bg-fuchsia-700 px-6 py-2 rounded">
                Guardar Cambios
            </button>
            
            <a href="reviews.php"
               class="bg-slate-600 hover:bg-slate-700 px-6 py-2 rounded">
                Cancelar
            </a>
        </div>
    </form>
</div>
</div>
</main>
<?php endif; ?>
</body>
</html>

