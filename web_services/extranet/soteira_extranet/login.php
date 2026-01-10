<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Buscamos usuario
    $stmt = $conn->prepare("SELECT id, nombre, email, password, rol FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verificamos contraseña (Hash o Texto plano para compatibilidad)
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // Guardamos sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_rol'] = $user['rol'];

            // TRUCO: Redirigimos al puerto 8080 (Intranet) dinámicamente
            $host = $_SERVER['SERVER_NAME']; 
            header("Location: http://$host:8080/dashboard.php");
            exit;
        }
    } 
    
    // Si falla
    header("Location: index.php?error=Usuario o contraseña incorrectos");
    exit;
}
?>