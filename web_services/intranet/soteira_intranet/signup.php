<?php
header('Content-Type: application/json');
require 'includes/db.php';

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nombre) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios"]);
    exit;
}


$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El correo ya está registrado"]);
    exit;
}
$stmt->close();


$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (nombre, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registro exitoso"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al registrar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>