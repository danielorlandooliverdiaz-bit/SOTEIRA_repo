<?php
session_start();
require_once 'includes/db.php';


// Verificamos si el usuario ya está logueado
$esta_logueado = isset($_SESSION['user_id']);
$nombre_usuario = isset($_SESSION['user_nombre']) ? $_SESSION['user_nombre'] : 'Usuario';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Soteira - Asistente Virtual</title>
</head>
<body>


<!-- =========================
     NAVBAR
     Barra de navegación principal (visible en la parte superior)
========================= -->



<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#tpl-inicio">
            <img src="assets/img/logo.png" alt="SOTEIRA" height="35" class="me-2">
            <span class="fw-bold">SOTEIRA</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navSoteira">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navSoteira">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link spa-link" href="#tpl-inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link spa-link" href="#tpl-empresa">La Empresa</a></li>
                <li class="nav-item"><a class="nav-link spa-link" href="#tpl-servicios">Servicios</a></li>
                <li class="nav-item"><a class="nav-link spa-link" href="#tpl-equipo">El Equipo</a></li>
                <li class="nav-item"><a class="nav-link spa-link" href="#tpl-contacto">Contacto</a></li>

                <!-- Si el usuario está logueado o no, muestra el nombre del usuario y un botón de logout/login -->

                <li class="nav-item ms-lg-3">
                    <?php if ($esta_logueado): ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-white me-2">
                                Hola, <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>
                            </span>
                            <a class="btn btn-danger fw-bold px-3 shadow-sm" href="logout.php">
                                LOGOUT
                            </a>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-warning fw-bold px-4 shadow-sm spa-link" href="#tpl-login">
                            ACCESO
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- =========================
     MAIN CONTAINER
     Área principal donde se renderiza el contenido visible dinámicamente
========================= -->


<main id="main-container" class="container mt-5 py-5"></main>
<!-- =========================
     TEMPLATES (sección oculta)
     Contiene plantillas HTML reutilizables que el frontend inserta en el DOM.
     Cada sección aquí representa un componente visual.
========================= -->

<div id="templates">
    
    <!-- TEMPLATE: INICIO -->
    <section id="tpl-inicio" class="page" >
        <div class="glass-card text-center py-5">
            <h1 class="display-3 fw-bold">Tu Guía Digital</h1>
            <p class="lead">Asistentes virtuales animados para una independencia tecnológica real.</p>
            <a href="#tpl-contacto" class="btn btn-primary spa-link">Contáctanos</a>
        </div>
    </section>

    <!-- TEMPLATE: EMPRESA / MISIÓN -->
    <section id="tpl-empresa" class="page">
        <div class="glass-card">
            <h2>Nuestra Misión</h2>
            <p>Soteira nace para ayudar a personas mayores a navegar en el mundo digital con avatares amigables.</p>
        </div>
    </section>

    <!-- TEMPLATE: SERVICIOS -->
    <section id="tpl-servicios" class="page">
        <h2 class="text-center mb-4">Servicios Soteira</h2>
        <div class="row g-4">
            <div class="col-md-4"><div class="glass-card text-center"><h3>Moodle</h3><p>Cursos guiados.</p></div></div>
            <div class="col-md-4"><div class="glass-card text-center"><h3>Nextcloud</h3><p>Nube segura.</p></div></div>
            <div class="col-md-4"><div class="glass-card text-center"><h3>Chat</h3><p>Comunicación fácil.</p></div></div>
        </div>
    </section>

    <!-- TEMPLATE: EQUIPO -->
    <section id="tpl-equipo" class="page">
        <div class="glass-card text-center">
            <h2>El Equipo</h2>
            <p>Daniel Oliver y Bobby Delhi: Innovación y Accesibilidad.</p>
        </div>
    </section>


    <!-- TEMPLATE: CONTACTO (formulario de registro) -->
    <section id="tpl-contacto" class="page">
        <div class="glass-card">
            <h3>¿Tienes una idea? Cuéntanosla</h3>
            <form action="contact-form.php" method="POST">
                <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre" required>
                <input type="email" name="email" class="form-control mb-3" placeholder="Tu Email" required>
                <textarea class="form-control mb-3" placeholder="Tu idea o sugerencia"></textarea>
                <button class="btn btn-primary w-100">Registrarse</button>
            </form>
        </div>
    </section>


    <!-- TEMPLATE: LOGIN (formulario de acceso) -->
    <section id="tpl-login" class="page">
        <div class="glass-card mx-auto" style="max-width: 400px;">
            <h2 class="text-center">Validación de Usuario</h2>
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
            <a href="#tpl-register" class="spa-link">Registrarse</a>
        </form>
        </div>
    </section>



    <!-- TEMPLATE: Register (formulario de registro) -->
    <section id="tpl-register" class="page">
        <div class="glass-card mx-auto" style="max-width: 400px;">
            <h2 class="text-center">Crear cuenta</h2>

           <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
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
        </div>
    </section>
</div>


<!-- =========================
     FOOTER
     Pie de página con información de contacto y enlaces legales
========================= -->
<footer class="footer-soteira mt-auto py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="navbar-brand text-yellow mb-3">SOTEIRA</h5>
                <p class="small text-light opacity-75">
                    Asistentes virtuales diseñados para la independencia digital 
                    de nuestros mayores. Cerrando la brecha generacional.
                </p>
            </div>

            <div class="col-md-2">
                <h6 class="text-uppercase fw-bold mb-3">Mapa Web</h6>
                <ul class="list-unstyled">
                    <li><a href="#tpl-inicio" class="footer-link spa-link">Inicio</a></li>
                    <li><a href="#tpl-empresa" class="footer-link spa-link">La Empresa</a></li>
                    <li><a href="#tpl-servicios" class="footer-link spa-link">Servicios</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="text-uppercase fw-bold mb-3">Contacto</h6>
                <p class="small mb-1">📍 Parque Tecnológico, Donostia</p>
                <p class="small mb-1">📞 +34 943 00 00 00</p>
                <p class="small">✉️ info@soteira.com</p>
            </div>

            <div class="col-md-3">
                <h6 class="text-uppercase fw-bold mb-3">Síguenos</h6>
                <div class="d-flex">
                    <a href="#" class="social-icon-box me-2"><img src="assets/img/TWITTER LOGO.png" alt="Twitter"></a>
                    <a href="#" class="social-icon-box me-2"><img src="assets/img/FACEBOOK.png" alt="Facebook"></a>
                    <a href="#" class="social-icon-box"><img src="assets/img/INSTAGRAM.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
        
        <hr class="my-4 border-secondary opacity-25">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="opacity-50">&copy; 2026 SOTEIRA S.L. Todos los derechos reservados.</small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="footer-link small me-3">Aviso Legal</a>
                <a href="#" class="footer-link small">Privacidad</a>
            </div>
        </div>
    </div>
</footer>

<!-- Script  SPA con JQuery -->

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/main.js" ></script>



<script src="assets/js/bootstrap.bundle.min.js"></script>


</body>
</html>
