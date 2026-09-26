<?php
session_start();
require "../../database/db.php";

// validación de server

if (!isset($_POST["user"]) || empty(trim($_POST["user"])) || !isset($_POST["pass"]) || empty(trim($_POST["pass"]))) {
    $_SESSION["error"] = "Usuario y contraseña son obligatorios";
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($_POST["user"]));


if ($stmt->rowCount()) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    if (password_verify($_POST["pass"], $usuario["password_hash"])) {
        $_SESSION["username"] = $usuario["username"];
        $_SESSION["login"] = true;

        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION["error"] = "Contraseña incorrecta";
    }
} else {
    $_SESSION["error"] = "Usuario incorrecto";
}

$conn = null;
header("Location: ../index.php");
exit;
