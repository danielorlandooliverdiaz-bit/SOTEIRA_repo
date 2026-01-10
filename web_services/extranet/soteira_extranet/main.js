$(document).ready(function () {
    let proyectos = [];

    // --- NUEVO: Función para actualizar la interfaz según el usuario ---
    function updateUserUI() {
        const session = localStorage.getItem('user_session');
        if (session) {
            const user = JSON.parse(session);
            $('#user-name-display').text(user.nombre); // Pone el nombre
            $('#user-info').removeClass('d-none');     // Muestra el saludo
            $('#nav-registro').addClass('d-none');     // Oculta el botón registro
        } else {
            $('#user-info').addClass('d-none');        // Oculta el saludo
            $('#nav-registro').removeClass('d-none');  // Muestra registro
        }
    }

    // --- NUEVO: Lógica del botón Logout ---
    $('#btn-logout').on('click', function() {
        if(confirm('¿Seguro que quieres cerrar sesión?')) {
            localStorage.removeItem('user_session'); // Borrar sesión
            updateUserUI(); // Actualizar interfaz
            showSection('#inicio'); // Volver al inicio
            window.location.hash = '#inicio';
            alert('Has cerrado sesión correctamente.');
        }
    });

    function showSection(target) {
        if (!target) return;
        $('main section').addClass('d-none');
        $(target).removeClass('d-none');
        
        // Cada vez que cambiamos de sección, verificamos la UI por si acaso
        updateUserUI(); 
    }

    $(document).on('click', 'a[href^="#"]', function (e) {
        const target = $(this).attr('href');
        if (target && target !== '#' && !$(this).data('bs-toggle')) {
            e.preventDefault();

            if (target.startsWith("#proyecto-detalle?id=")) {
                const id = parseInt(target.split('=')[1]);
                const proyecto = proyectos.find(p => p.id === id);
                if (proyecto) {
                    $('#proyecto-titulo').text(proyecto.titulo);
                    $('#proyecto-imagen').attr('src', `../${proyecto.imagen}`);
                    $('#proyecto-descripcion').text(proyecto.descripcion);
                    $('#proyecto-fecha').text(proyecto.fecha);
                    showSection('#proyecto-detalle');
                    window.location.hash = target;
                }
            } else if (target === "#registro") {
                if (localStorage.getItem('user_session')) {
                    // Si ya está logueado, no le dejamos ir a registro, lo mandamos a inicio
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

    $('#register-form').on('submit', function (e) {
        e.preventDefault();
        const nombre = $('#nombre-registro').val();
        const email = $('#email-registro').val();
        const password = $('#password-registro').val();

        if (nombre && email && password) {
            $.ajax({
                url: 'signup.php',
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

    $('#login-form').on('submit', function (e) {
        e.preventDefault();
        const email = $('#email-login').val();
        const password = $('#password-login').val();

        if (email && password) {
            $.ajax({
                url: 'login.php',
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
                        
                        // --- AQUÍ ACTUALIZAMOS LA UI AL ENTRAR ---
                        updateUserUI(); 

                        $('#login-form')[0].reset();
                        showSection('#inicio');
                        window.location.hash = '#inicio';
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

    // ... (El resto de funciones loadProyectos, handleInitialSection, etc. sigue igual)
    function loadProyectos() {
        fetch('../data.json')
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
                                <img src="../${proyecto.imagen}" class="card-img-top img-fluid" alt="${proyecto.titulo}">
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

    function handleInitialSection() {
        const hash = window.location.hash;
        updateUserUI(); // Verificar usuario al cargar la página
        if (hash) {
            if (hash.startsWith("#proyecto-detalle?id=")) {
                if (proyectos.length === 0) return;
                const id = parseInt(hash.split('=')[1]);
                const proyecto = proyectos.find(p => p.id === id);
                if (proyecto) {
                    $('#proyecto-titulo').text(proyecto.titulo);
                    $('#proyecto-imagen').attr('src', `../${proyecto.imagen}`);
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

    loadProyectos();
});