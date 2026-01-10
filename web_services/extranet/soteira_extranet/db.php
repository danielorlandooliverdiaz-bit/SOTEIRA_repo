<?php
$host = "soteira_db";
$user = "root";
$pass = "admin";
$db   = "soteira_db";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Falló la conexión con MySQL: " . $conn->connect_error]));
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($sql_create_db)) {
    die(json_encode(["status" => "error", "message" => "Error al crear la base de datos: " . $conn->error]));
}

$conn->select_db($db);

// ESTA ES LA CLAVE: Ahora la tabla se crea con la columna 'rol'
$sql_create_table = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql_create_table)) {
    die(json_encode(["status" => "error", "message" => "Error al crear la tabla de usuarios: " . $conn->error]));
}
?>