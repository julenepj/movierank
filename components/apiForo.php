<?php 
require ("../database/db.php");

$sql = "INSERT INTO foro (user, pregunta, descripcion, tema) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $_POST["user"], 
    $_POST["pregunta"], 
    $_POST["descripcion"],
    $_POST["tema"]
]);

if ($stmt->rowCount()) {
    $conn = null;
    header("Location: foro.php");
    exit;
}

$conn = null;
?>
