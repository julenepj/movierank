<?php
require("../../database/db.php");

// 1. Recogemos los datos del formulario
$username = $_POST["user"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["pass"] ?? '';

if (empty($email) || empty($password)) {
    die("Por favor, completa todos los campos obligatorios.");
}

// 2. Comprobar si el correo ya está registrado en la base de datos
$sqlCheck = "SELECT id FROM users WHERE email = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->execute([$email]);

if ($stmtCheck->rowCount() > 0) {
    // El email ya existe, redirigimos o mostramos error amigable
    echo "El correo electrónico ya está registrado. <a href='../../index.php'>Volver</a>";
    exit;
}

// 3. Si no existe, procedemos a insertar el nuevo usuario
$sql = "INSERT INTO users (username, email, password_hash, created_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

$stmt->execute([
    $username,
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    date("Y-m-d H:i:s")
]);

if ($stmt->rowCount()) {
    $conn = null;
    header("Location: ../../index.php");
    exit;
}

$conn = null;
?>