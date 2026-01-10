<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si existe
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        header("Location: signup_form.php?error=El email ya existe");
        exit;
    }

    // Insertar nuevo usuario
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
    $stmt->bind_param("sss", $nombre, $email, $hashed);

    if ($stmt->execute()) {
        // Éxito: mandar al home para que se loguee
        header("Location: index.php?msg=Registro completado. Inicia sesión.");
    } else {
        header("Location: signup_form.php?error=Error al guardar");
    }
}
?>