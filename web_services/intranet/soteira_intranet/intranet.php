<?php
session_start();
require_once 'utils/db.php';

if (
    !isset($_SESSION['user_rol']) ||
    $_SESSION['user_rol'] !== 'admin'
) {
    header("Location: index.html");
    exit;
}

$total_users = 0;
$result_recent = [];

if (isset($conn) && $conn instanceof mysqli) {
    $sql_count = "SELECT COUNT(*) AS total FROM users";
    if ($result = $conn->query($sql_count)) {
        $row = $result->fetch_assoc();
        $total_users = (int) $row['total'];
    }

    $sql_recent = "
        SELECT nombre, email, created_at
        FROM users
        ORDER BY created_at DESC
        LIMIT 5
    ";
    $result_recent = $conn->query($sql_recent);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet - SOTEIRA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">

    <style>
        .dashboard-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .service-list-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .service-list-item:hover {
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }
    </style>
</head>
<body>

<header class="text-white py-3 px-4 d-flex justify-content-between align-items-center">
    <h2 class="font-bonello m-0">
        SOTEIRA <span class="fs-6 text-warning">| Admin Panel</span>
    </h2>
    <div>
        <span class="me-3">
            Hola, <?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?>
        </span>
        <a href="utils/logout.php" class="btn btn-outline-danger btn-sm">
            Cerrar Sesión
        </a>
    </div>
</header>

<main class="container my-5">

    <h1 class="text-white mb-4 font-titulo1">Resumen General</h1>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card dashboard-card p-4 text-center h-100">
                <h3 class="card-title">Usuarios Registrados</h3>
                <p class="display-1 fw-bold text-warning">
                    <?= $total_users ?>
                </p>
                <p class="text-light">Total histórico en base de datos</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card dashboard-card p-4 text-center h-100">
                <h3 class="card-title">Estado del Servidor</h3>
                <p class="display-6 mt-3 text-success">🟢 Sistema Activo</p>
                <p class="text-light mt-3">Base de datos: Conectada</p>
            </div>
        </div>
    </div>

    <hr class="border-light my-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card dashboard-card">
                <div class="card-header bg-transparent border-bottom border-secondary">
                    <h3 class="text-white m-0 text-center">
                        Acceso a servicios internos (Docker)
                    </h3>
                </div>

                <div class="card-body p-4">
                    <p class="text-center text-light mb-4">
                        Selecciona el servicio que deseas gestionar:
                    </p>

                    <div class="list-group">

                        <div class="service-list-item d-flex justify-content-between align-items-center p-3">
                            <span class="text-white fw-bold">Almacenamiento en la nube</span>
                            <a href="http://cloud.soteira.local" target="_blank" class="btn btn-secondary px-4">
                                Nextcloud
                            </a>
                        </div>

                        <div class="service-list-item d-flex justify-content-between align-items-center p-3">
                            <span class="text-white fw-bold">Sistema de incidencias</span>
                            <a href="http://peppermint.soteira.local" target="_blank" class="btn btn-primary px-4">
                                Peppermint
                            </a>
                        </div>

                        <div class="service-list-item d-flex justify-content-between align-items-center p-3">
                            <span class="text-white fw-bold">Plataforma de mensajería</span>
                            <a href="http://chat.soteira.local" target="_blank" class="btn btn-danger px-4">
                                Rocket.Chat
                            </a>
                        </div>

                        <div class="service-list-item d-flex justify-content-between align-items-center p-3">
                            <span class="text-white fw-bold">Plataforma educativa</span>
                            <a href="http://moodle.soteira.local" target="_blank" class="btn btn-secondary px-4">
                                Moodle
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="index.html" class="btn btn-outline-light">
            ← Volver a la Web Principal
        </a>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
