<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Recibir y limpiar datos
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $rol_defecto = 'cliente'; 

    // 2. Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password)) {
        echo "<script>alert('Por favor complete todos los campos.'); window.history.back();</script>";
        exit;
    }

    // 3. Verificar si el email ya existe
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('Ese correo ya está registrado. Intenta iniciar sesión.'); window.location.href='index.php#tpl-login';</script>";
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();

    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $stmt_insert = $conn->prepare("INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("ssss", $nombre, $email, $password_hash, $rol_defecto);

    if ($stmt_insert->execute()) {
        // Registro exitoso: Redirigir al login para que entre
        header("Location: index.php");
    } else {
        echo "Error en el registro: " . $conn->error;
    }

    $stmt_insert->close();
    $conn->close();

} else {
    // Si intentan entrar directo a register.php sin enviar formulario
    header("Location: index.php");
    exit;
}
?>