<?php
// config.php

$servername = "localhost";
$username = "JOSE12"; 
$password = "JOSE12"; 
$dbname = "iuknow"; 

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo "Error de conexión: " . $conn->connect_error; // Muestra más detalles del error
    die();
}

?>
