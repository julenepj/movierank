<?php

/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $poster_path = $_POST["poster_path"];

    if (!isset($_SESSION["favoritos"])) {
        $_SESSION["favoritos"] = [];
    }

    // Verificar si la película ya está en favoritos
    $index = -1;
    foreach ($_SESSION["favoritos"] as $key => $fav) {
        if ($fav["id"] == $id) {
            $index = $key;
            break;
        }
    }

    if ($index !== -1) {
        // Si la película ya está en favoritos, eliminarla
        unset($_SESSION["favoritos"][$index]);
        $_SESSION["favoritos"] = array_values($_SESSION["favoritos"]); // Reindexar el array
    } else {
        // Si la película no está en favoritos, agregarla
        $_SESSION["favoritos"][] = [
            "id" => $id,
            "title" => $title,
            "poster_path" => $poster_path
        ];
    }

    // Redirigir a la página de favoritos
    header("Location: add_favorite.php");
    exit();
}*/
session_start();
include "../database/db.php";

if (!isset($_SESSION["username"])) {
    header("Location: ../index.php");
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

// Verificar si el id de la película a eliminar está disponible
if (isset($_POST['id'])) {
    $movie_id = $_POST['id'];

   

    // Eliminar la película de los favoritos del usuario
    $sql = "DELETE FROM favoritos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$movie_id]);

    // Redirigir al usuario a la página de favoritos después de eliminar la película
    header("Location: add_favorite.php");
    exit();
} else {
    // Si no se ha recibido el id, redirigir de nuevo
    echo "Error: No se ha especificado la película a eliminar.";
    exit();
}



