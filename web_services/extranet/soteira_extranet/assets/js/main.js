$(document).ready(function() {

    // Función para manejar el enrutado (ruteo)
    function router() {
        // 1. Obtener el hash (p.ej., #tpl-inicio) o valor por defecto
        var hash = window.location.hash || '#tpl-inicio';

        // 2. Ocultar todas las páginas
        $('.page').hide();

        // 3. Mostrar la página que coincide con el hash
        // Usamos el hash directamente como selector de ID
        if ($(hash).length) {
            $(hash).fadeIn(300); // Efecto de desvanecimiento
        } else {
            // Si el hash no existe (404), ir a inicio
            $('#tpl-inicio').show();
        }

        // 4. Actualizar la clase activa en el menú
        $('nav a').removeClass('active');
        $('nav a[href="' + hash + '"]').addClass('active');
    }

    // Escuchar cambios de hash (cuando el usuario hace clic en enlaces o usa el botón Atrás)
    $(window).on('hashchange', router);

    // Cargar la página correcta al cargar inicialmente
    router();
    
    // Ejemplo: interacción dentro de una página específica
    $('#alert-btn').click(function() {
        alert("¡Has hecho clic en un botón en la página de Contacto!");
    });

});
