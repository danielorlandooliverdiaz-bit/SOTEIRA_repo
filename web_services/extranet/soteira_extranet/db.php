<?php
$host = "soteira_db"; // Nombre del contenedor en Docker
$user = "root";
$pass = "admin";
$db   = "soteira_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>