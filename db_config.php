<?php
$servername = "localhost";
$username = "root";
$password = ""; // Cambia la contraseña si es necesario
$dbname = "alquiler_departamentos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
