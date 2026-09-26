<?php
require("database/db.php");

$nuevaPassword = "admin123"; // Puedes poner aquí la contraseña que quieras
$hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

$sql = "UPDATE users SET email = 'admin@notflix.com', password_hash = ? WHERE id = 4";
$stmt = $conn->prepare($sql);
$stmt->execute([$hash]);

echo "¡Contraseña actualizada con éxito! Ya puedes iniciar sesión con la contraseña: <b>" . $nuevaPassword . "</b>";
?>