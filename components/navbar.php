<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    // Redirect to login page
    header("Location: ../index.php");
    exit;
}
?>

<div class="flex">
</div>
<nav class=" h-screen bg-slate-900 shadow-[0px_8px_28px_0px_rgba(1,5,17,0.30)] flex flex-col fixed" style="width: 250px;"> <!--he quitado w-72, para que sea mas pequeño-->
    <!-- Header con logo -->
    <header class="px-7 py-6 border-b border-slate-700">
        <div class="flex items-center gap-3">

            <h1 class="text-white text-xl font-semibold">
            <?php
                echo "Bienvenid@ " . htmlspecialchars($_SESSION["username"]) . "<br>";

                setcookie("language", "es", time() + 3600, "/");

                if (isset($_COOKIE["language"])) {
                    echo "Idioma: " . htmlspecialchars($_COOKIE["language"]);
                } else {
                    echo "Idioma: No definido";
                }
                ?>
            </h1>
        </div>
    </header>

    <!-- Barra de búsqueda -->
    <section class=" px-7 py-6">
        <div class="relative">
            <input
                type="search"
                placeholder="Search for..."
                class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-gray-700 rounded-md text-indigo-300 text-sm focus:outline-none focus:border-fuchsia-500">
            <svg class="w-4 h-4 absolute left-3 top-3 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </section>

    <!-- Menú principal -->
    <section class="flex-1 overflow-y-auto px-7">
        <ul class="space-y-2">
            <li>
            <a class="text-fuchsia-500 text-sm font-medium mb-2" href="/components/header.php">Inicio</a>


            </li>

            <li class="pt-4">
                <a class="text-indigo-300 text-sm font-medium mb-2" href="/components/add_favorite.php">Mis Peliculas</a>

            </li>
            
            <li class="pt-4">
                <a class="text-indigo-300 text-sm font-medium mb-2" href="/pages/nota.php">Mis Reviews</a> <!-- Añadir aqui un CRUD, que puedas publicar tus opiniones-->
            </li>
            
            <li class="pt-4">
                <a class="text-indigo-300 text-sm font-medium mb-2" href="/components/foro.php">Foro</a> <!-- Añadir aqui un CRUD, que puedas publicar preguntas-->
            </li>
        </ul>
    </section>

    <!-- Perfil de usuario -->
    <footer class="p-7 border-t border-slate-700">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-8 h-8 bg-fuchsia-500 rounded-full"></div>
                <div class="absolute inset-0 border-2 border-violet-200 rounded-full"></div>
            </div>

            <div>
                <p class="text-white text-sm font-medium">
                    <?php
                    echo htmlspecialchars($_SESSION["username"]);
                    ?>
                </p>
                <a class="text-indigo-300 text-xs" href="../includes/user/actualizar_datos.php">Ajustes de cuenta</a>
            </div>
        </div>
    </footer>
</nav>