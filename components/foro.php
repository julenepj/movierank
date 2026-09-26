<?php 
SESSION_START();
require ("../database/db.php");

// Consulta para obtener todos los registros de la tabla "foro"
$sql = "SELECT * FROM foro";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Obtener los resultados como un array asociativo
$foroPreguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Foro - MovieRank</title>
</head>

<body class="bg-slate-900 text-white">
    <?php include('navbar.php'); ?>

    <!-- Contenido Principal -->
    <main class="ml-72 p-8">
        <div class="max-w-7xl mx-auto">
            <nav class="mb-6 flex justify-between">
                <h1 class="text-3xl font-bold">Preguntas de Usuarios</h1>
                <ul class="flex justify-end bg-slate-900 p-2 ml-20">
                    <li class="mr-6">
                        <a class="text-white bg-fuchsia-600 hover:bg-fuchsia-700 px-4 py-2 rounded" href="../includes/user/cerrar.php">Desconectar</a>
                    </li>
                </ul>
            </nav>
            <hr class="my-4 border-gray-600">
        <button id="toggleFormBtn" class="fixed bottom-5 right-5 bg-indigo-500 text-white p-4 rounded-full text-3xl w-16 h-16 flex items-center justify-center shadow-lg">
            +
        </button>

        <div>
            <form action="apiForo.php" method="post" id="formPregunta" class="hidden">
                <h2 class="text-2xl font-bold text-center mb-4">Añadir pregunta</h2>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="user">Usuario</label>
                    <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400" type="text" name="user" placeholder="Tu usuario" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="pregunta">Pregunta</label>
                    <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400" type="text" name="pregunta" placeholder="Añade tu pregunta" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="descripcion">Descripción</label>
                    <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400" type="text" name="descripcion" placeholder="Añade la descripción" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="tema">Tema</label>
                    <input class="w-full px-3 py-2 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-gray-400" type="text" name="tema" placeholder="Añade el tema" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" type="submit">Añadir pregunta</button>
                </div>
            </form>
        </div>
    
        <div class="mt-8">
            <h2 class="text-xl font-bold text-center">Listado de Preguntas</h2>
            <div class="flex justify-end mb-4">
                <button id="sortTemaBtn" class="text-white bg-indigo-600 px-4 py-2 rounded flex items-center">
                    Ordenar por Tema <span id="arrow" class="ml-2">⬇</span>
                </button>
            </div>
            <?php if ($foroPreguntas): ?>
                <div id="foroContainer" class="space-y-4">
                    <?php foreach ($foroPreguntas as $pregunta): ?>
                        <div class="bg-gray-800 p-4 rounded-lg shadow" data-tema="<?= htmlspecialchars($pregunta['tema']) ?>">
                        <?php if ($_SESSION["username"] == "Admin"): ?>
                           
                            <form action="eliminarPregunta.php" method="post" style="display:inline; float:right;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta pregunta?');">
                                    <input type="hidden" name="id_pregunta" value="<?= htmlspecialchars($pregunta['id_pregunta']) ?>">
                                    <button type="submit" class="text-red-500 ">X</button>
                            </form>
                            <?php endif; ?>
                            <p class="text-sm text-gray-500">Usuario: <?= htmlspecialchars($pregunta['user']) ?> | Tema: <span class="tema">
                                    <?= htmlspecialchars($pregunta['tema']) ?>
                                </span></p>
                            <h2 class="text-lg font-bold"><?= htmlspecialchars($pregunta['pregunta']) ?></h2>
                            <p class="text-gray-400">
                                <?= htmlspecialchars($pregunta['descripcion']) ?>
                            </p>
                            
                            <?php if (!empty($pregunta['respuesta'])): ?>
                            <p class="text-sm text-gray-500">
                                Respuesta de <?= htmlspecialchars($pregunta['user_respuesta']) ?>: <?= htmlspecialchars($pregunta['respuesta']) ?>
                            </p>
                        <?php else: ?>
                            <form id="respuestaForm" action="responder.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_pregunta" value="<?= htmlspecialchars($pregunta['id_pregunta']) ?>">
                                <input type="hidden" name="user_respuesta" value="<?= htmlspecialchars($_SESSION['username'] ) ?>">
                                <input type="hidden" name="respuesta" id="respuestaInput">
                                <button type="button" class="text-green-500" onclick="mostrarInput()">Responder</button>
                            </form> 
                               
                            <?php endif; ?>
                                <script>
                                function mostrarInput() {
                                    let respuesta = prompt("Escribe tu respuesta:");
                                    if (respuesta !== null && respuesta.trim() !== "") {
                                        document.getElementById("respuestaInput").value = respuesta;
                                        document.getElementById("respuestaForm").submit();
                                    }
                                }
                                </script>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-400">No hay preguntas en el foro.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById("toggleFormBtn").addEventListener("click", function () {
            document.getElementById("formPregunta").classList.toggle("hidden");
        });

        let ascending = true;
        document.getElementById("sortTemaBtn").addEventListener("click", function () {
            let container = document.getElementById("foroContainer");
            let preguntas = Array.from(container.children);
            
            preguntas.sort((a, b) => {
                let temaA = a.getAttribute("data-tema").toLowerCase();
                let temaB = b.getAttribute("data-tema").toLowerCase();
                return ascending ? temaA.localeCompare(temaB) : temaB.localeCompare(temaA);
            });
            
            preguntas.forEach(pregunta => container.appendChild(pregunta));
            
            ascending = !ascending;
            document.getElementById("arrow").textContent = ascending ? "⬇" : "⬆";
        });
    </script>
</body>
</html>
