<?php
session_start();
require_once 'includes/db.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Consulta segura
    $stmt = $conn->prepare("SELECT id, nombre, email, password, rol FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verificación híbrida (Hash o Texto Plano)
        $pass_ok = password_verify($password, $user['password']) || ($password === $user['password']);

        if ($pass_ok) {
            // LOGIN CORRECTO: Guardamos sesión y redirigimos
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_rol'] = $user['rol'];

            // REDIRECCIÓN SERVIDOR (Infalible)
            header("Location: index.php?success=Login correcto");
            exit;
        }
    }

    // Si falla, volvemos al index con error
    header("Location: index.php?error=Credenciales incorrectas");
    exit;
}