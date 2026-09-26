<?php
$apiKey = "7873de2e275ccb8a528ef53acf057b8b";  // Reemplázala con tu API Key

// Obtener el número de página actual desde la URL, si no existe, establecer en 1
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Construir la URL con el número de página dinámico
$url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=es-ES&page=$pagina_actual";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    die("Error al obtener los datos de TMDb.");
}

$data = json_decode($response, true);
