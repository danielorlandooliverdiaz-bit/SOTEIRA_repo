<?php
session_start();

// Detectamos la IP para los enlaces dinámicos
$host_ip = $_SERVER['SERVER_NAME'];

// Si ya está logueado, lo mandamos directo al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SOTEIRA</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center vh-100">

    <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%; background: rgba(255,255,255,0.9);">
        <div class="text-center mb-4">
            <img src="assets/img/logo.png" alt="Soteira" style="height: 60px;">
            <h3 class="mt-2">Acceso Intranet</h3>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <div class="mt-3 text-center">
            <small>¿No tienes cuenta? <a href="http://<?= $host_ip ?>:8081" >Regístrate en la Extranet</a></small>
        </div>
    </div>

</body>
</html>