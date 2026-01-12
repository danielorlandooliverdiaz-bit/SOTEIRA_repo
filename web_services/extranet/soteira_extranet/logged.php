<?php
// echo 




?>

<h1>LOGEADo</h1>
<div>
        <span class="me-3">
            Hola, <b><?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></b>
        </span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">
            Cerrar Sesión
        </a>
    </div>