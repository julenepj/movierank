<?php
session_start();
require ("../database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["username"]) && $_SESSION["username"] == "Admin") {
    $id = $_POST["id_pregunta"];
    
    $sql = "DELETE FROM foro WHERE id_pregunta = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "success";
        header("Location: foro.php");   
    } else {
        echo "error";
    }
} else {
    echo "error";
}

