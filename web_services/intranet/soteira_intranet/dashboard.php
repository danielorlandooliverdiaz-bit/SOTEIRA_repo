<?php
session_start();
require_once 'includes/db.php'; 

// Detectamos la IP para los enlaces dinámicos
$host_ip = $_SERVER['SERVER_NAME'];

// --- SEGURIDAD ---
if (!isset($_SESSION['user_id'])) {
    
    header("Location: index.php");
    exit;
}

// Variable para saber si es admin
$is_admin = (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin');

$total_users = 0;

// Solo cargamos estadísticas si es admin
if ($is_admin && isset($conn) && $conn instanceof mysqli) {
    $sql_count = "SELECT COUNT(*) AS total FROM users";
    if ($result = $conn->query($sql_count)) {
        $row = $result->fetch_assoc();
        $total_users = (int) $row['total'];
    }
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
        /* Efecto hover para los botones de servicio */
        .service-list-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s;
            text-decoration: none; /* Quitar subrayado */
            color: white;
        }
        .service-list-item:hover {
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
            color: #ffc107; /* Color dorado al pasar el ratón */
        }
    </style>
</head>
<body class="bg-dark"> <header class="text-white py-3 px-4 d-flex justify-content-between align-items-center">
    <h2 class="m-0">
        SOTEIRA <span class="fs-6 text-warning">| <?= $is_admin ? 'Admin Panel' : 'Área de Usuario' ?></span>
    </h2>
    <div>
        <span class="me-3">
            Hola, <b><?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></b>
        </span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">
            Cerrar Sesión
        </a>
    </div>
</header>

<main class="container my-5">

    <?php if ($is_admin): ?>
    <h1 class="text-white mb-4">Resumen del Sistema</h1>
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card dashboard-card p-4 text-center h-100">
                <h3 class="card-title">Usuarios Totales</h3>
                <p class="display-1 fw-bold text-warning"><?= $total_users ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card dashboard-card p-4 text-center h-100">
                <h3 class="card-title">Estado Servidor</h3>
                <p class="display-6 mt-3 text-success">🟢 En Línea</p>
            </div>
        </div>
    </div>
    <hr class="border-light my-5">
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card dashboard-card">
                <div class="card-header bg-transparent border-bottom border-secondary">
                    <h3 class="text-white m-0 text-center">
                        Tus Servicios Disponibles
                    </h3>
                </div>

                <div class="card-body p-4">
                    <p class="text-center text-light mb-4">
                        Accede a las aplicaciones con tu cuenta:
                    </p>

                    <div class="list-group">
                       <div class="d-flex justify-content-between align-items-center service-list-item p-3">
                            <span class="fw-bold fs-5">☁️ Nube Corporativa</span>
                            <a href="http://<?= $host_ip ?>:8083" target="_blank" class="btn btn-secondary px-4">Acceder</a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center service-list-item p-3">
                            <span class="fw-bold fs-5">🎫 Soporte / Tickets</span>
                            <a href="http://<?= $host_ip ?>:8085" target="_blank" class="btn btn-primary px-4">Acceder</a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center service-list-item p-3">
                            <span class="fw-bold fs-5">💬 Chat Interno</span>
                            <a href="http://<?= $host_ip ?>:8084" target="_blank" class="btn btn-danger px-4">Acceder</a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center service-list-item p-3">
                            <span class="fw-bold fs-5">🎓 Campus Virtual</span>
                            <a href="http://<?= $host_ip ?>:8082" target="_blank" class="btn btn-warning px-4 text-dark">Acceder</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="logout.php" class="text-white text-decoration-none opacity-75">← Cerrar y Volver</a>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>