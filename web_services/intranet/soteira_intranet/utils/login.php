<?php
session_start(); 
header('Content-Type: application/json');
require 'db.php'; 

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Faltan datos"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, nombre, password, rol FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_rol'] = $user['rol'];

        echo json_encode([
            "status" => "success", 
            "message" => "Bienvenido " . $user['nombre'],
            "user" => [
                "id" => $user['id'],
                "nombre" => $user['nombre'],
                "email" => $email,
                "rol" => $user['rol'] 
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
}

$stmt->close();
$conn->close();
?>