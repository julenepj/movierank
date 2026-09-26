<?php 
require ("../database/db.php");

$sql = "UPDATE foro SET respuesta = ?, user_respuesta = ? WHERE id_pregunta = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $_POST["respuesta"], 
    $_POST["user_respuesta"],
    $_POST["id_pregunta"]
]);

if ($stmt->rowCount()) {
    $conn = null;
    header("Location: foro.php");
    exit;
}

$conn = null;
?>
