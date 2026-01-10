<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Soteira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <h3 class="text-center text-primary">Crear Cuenta</h3>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger p-2"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
        <p class="text-center mt-3"><a href="index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>