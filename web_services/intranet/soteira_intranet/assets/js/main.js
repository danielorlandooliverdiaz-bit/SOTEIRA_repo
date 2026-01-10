$(document).ready(function () {
    let proyectos = [];

    // --- Función para actualizar la interfaz según el usuario ---
    function updateUserUI() {
        const session = localStorage.getItem('user_session');
        if (session) {
            const user = JSON.parse(session);
            $('#user-name-display').text(user.nombre); 
            $('#user-info').removeClass('d-none');     
            $('a[href="#registro"]').parent().addClass('d-none');     
        } else {
            $('#user-info').addClass('d-none');        
            $('a[href="#registro"]').parent().removeClass('d-none');  
        }
    }

    // --- Lógica del botón Logout (SPA) ---
    $('#btn-logout').on('click', function() {
        if(confirm('¿Seguro que quieres cerrar sesión?')) {
            localStorage.removeItem('user_session'); // Borrar sesión del navegador
            
            // Si quieres que el logout también mate la sesión PHP (por si acaso), descomenta esto:
            // window.location.href = 'utils/logout.php';
            
            // Comportamiento SPA original:
            updateUserUI(); 
            showSection('#inicio'); 
            window.location.hash = '#inicio';
            alert('Has cerrado sesión correctamente.');
        }
    });

    // --- Función CORE de la SPA (Mostrar/Ocultar secciones) ---
    function showSection(target) {
        if (!target) return;
        $('main section').addClass('d-none');
        $(target).removeClass('d-none');
        
        updateUserUI(); 
    }

    // --- Navegación SPA (Interceptar clicks en enlaces) ---
    $(document).on('click', 'a[href^="#"]', function (e) {
        const target = $(this).attr('href');
        if (target && target !== '#' && !$(this).data('bs-toggle')) {
            e.preventDefault();

            if (target.startsWith("#proyecto-detalle?id=")) {
                const id = parseInt(target.split('=')[1]);
                const proyecto = proyectos.find(p => p.id === id);
                if (proyecto) {
                    $('#proyecto-titulo').text(proyecto.titulo);
                    $('#proyecto-imagen').attr('src', proyecto.imagen);
                    $('#proyecto-descripcion').text(proyecto.descripcion);
                    $('#proyecto-fecha').text(proyecto.fecha);
                    showSection('#proyecto-detalle');
                    window.location.hash = target;
                }
            } else if (target === "#registro") {
                if (localStorage.getItem('user_session')) {
                    alert('Ya estás registrado e identificado.');
                    showSection('#inicio');
                } else {
                    showSection('#registro');
                    window.location.hash = '#registro';
                }
            } else {
                showSection(target);
                window.location.hash = target;
            }
        }
    });

    // --- Registro (AJAX) ---
    $('#register-form').on('submit', function (e) {
        e.preventDefault();
        const nombre = $('#nombre-registro').val();
        const email = $('#email-registro').val();
        const password = $('#password-registro').val();

        if (nombre && email && password) {
            $.ajax({
                url: 'utils/signup.php',
                type: 'POST',
                data: {
                    nombre: nombre,
                    email: email,
                    password: password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('¡Registro completado! Ahora puedes iniciar sesión.');
                        $('#register-form')[0].reset();
                        showSection('#login');
                        window.location.hash = '#login';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Hubo un error de conexión con el servidor.');
                }
            });
        } else {
            alert('Por favor, completa todos los campos.');
        }
    });

    // --- Login (AJAX con lógica Admin vs Cliente) ---
    $('#login-form').on('submit', function (e) {
        e.preventDefault();
        const email = $('#email-login').val();
        const password = $('#password-login').val();

        if (email && password) {
            $.ajax({
                url: 'utils/login.php',
                type: 'POST',
                data: {
                    email: email,
                    password: password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        localStorage.setItem('user_session', JSON.stringify(response.user));
                        
                        // --- AQUÍ ESTÁ EL CAMBIO ---
                        if (response.user.rol === 'admin') {
                            // SI ES ADMIN: Redirigimos a la intranet (Sale de la SPA)
                            window.location.href = 'intranet.php';
                        } else {
                            // SI ES CLIENTE: Mantenemos tu SPA original
                            updateUserUI(); 
                            $('#login-form')[0].reset();
                            showSection('#inicio');
                            window.location.hash = '#inicio';
                        }
                        // ---------------------------

                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Hubo un error de conexión con el servidor.');
                }
            });
        } else {
            alert('Por favor, completa todos los campos.');
        }
    });

    // --- Carga de Proyectos (JSON) ---
    function loadProyectos() {
        fetch('data.json')
            .then(response => response.json())
            .then(data => {
                proyectos = data.proyectos;
                const container = $('#proyectos-container');
                container.empty();
                proyectos.forEach(proyecto => {
                    const collapseId = `collapse-proyecto-${proyecto.id}`;
                    const card = `
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm card-shadow-hover">
                                <img src="${proyecto.imagen}" class="card-img-top img-fluid" alt="${proyecto.titulo}">
                                <div class="card-body">
                                    <h5 class="card-title">${proyecto.titulo}</h5>
                                    <p class="card-text">${proyecto.descripcion.substring(0, 100)}...</p>
                                    <a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#${collapseId}" role="button">Más info</a>
                                    <div class="collapse mt-2" id="${collapseId}">
                                        <p>Para ver los detalles, <a href="#proyecto-detalle?id=${proyecto.id}" class="text-decoration-underline">pincha aquí</a>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(card);
                });
                handleInitialSection();
            })
            .catch(error => {
                console.error('Error al cargar los proyectos:', error);
                $('#proyectos-container').html('<p class="text-danger text-center">No se pudieron cargar los proyectos.</p>');
            });
    }

    // --- Manejo de la URL inicial (Hash) ---
    function handleInitialSection() {
        const hash = window.location.hash;
        updateUserUI(); // Verificar usuario al cargar
        if (hash) {
            if (hash.startsWith("#proyecto-detalle?id=")) {
                if (proyectos.length === 0) return;
                const id = parseInt(hash.split('=')[1]);
                const proyecto = proyectos.find(p => p.id === id);
                if (proyecto) {
                    $('#proyecto-titulo').text(proyecto.titulo);
                    $('#proyecto-imagen').attr('src', proyecto.imagen);
                    $('#proyecto-descripcion').text(proyecto.descripcion);
                    $('#proyecto-fecha').text(proyecto.fecha);
                    showSection('#proyecto-detalle');
                } else {
                    showSection('#inicio');
                }
            } else {
                showSection(hash);
            }
        } else {
            showSection('#inicio');
        }
    }

    // --- Formulario de Contacto ---
    $('#contact-form').on('submit', function (e) {
        e.preventDefault();
        const email = $('#email-contacto').val();
        if (validateEmail(email)) {
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {});
            myModal.show();
            $('#contact-form')[0].reset();
        } else {
            alert('Por favor, introduce un correo electrónico válido.');
        }
    });

    function validateEmail(email) {
        const re = /^(([^<>()[\.,;:\s@\"]+(\.[^<>()[\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // --- Buscador de Proyectos ---
    $('#project-search').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();
        $('#proyectos-container .col-12').each(function () {
            const title = $(this).find('.card-title').text().toLowerCase();
            const desc = $(this).find('.card-text').text().toLowerCase();
            if (title.includes(searchTerm) || desc.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Iniciar carga
    loadProyectos();
});