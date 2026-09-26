<?php

session_start();

try {
    $usu = $_SESSION["username"];
    $new_username = $_POST["new_username"];
    $correo = $_POST["email"];
    $antiguo = $_POST["oldpass"];
    $nuevo = $_POST["newpass"];

    include("../../database/db.php");

    // primero vamos a buscar y ver que el password esta ok
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$usu]);

    if ($stmt->rowCount()) { //aqui recupero el password
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); //relleno el array de datos de user

        if (password_verify($antiguo, $usuario["password_hash"])) { //compruebo que el pass
            $sql = "UPDATE users SET username=?, password_hash=?, email=? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$new_username, password_hash($nuevo, PASSWORD_DEFAULT), $correo, $usu]);

            $_SESSION["username"] = $new_username;



            $conn = null;
            header("Location: actualizar_datos.php?success=1");
            exit;
        } else {
            throw new Exception("Contraseña antigua incorrecta.");
        }
    } else {
        throw new Exception("Usuario no encontrado.");
    }
} catch (Exception $e) {
    // Log the error message or display it to the user
    error_log($e->getMessage());
    header("Location: actualizar_datos.php?error=" . urlencode($e->getMessage()));
    exit;
}
