$(document).ready(function() {
    // Función para renderizar secciones
    function render(hash) {
        const id = hash || '#inicio';
        const content = $('#tpl-' + id.replace('#', '')).html();
        
        $('#view-container').fadeOut(200, function() {
            $(this).html(content).fadeIn(200);
        });
        
        // Actualizar estado activo en menú
        $('.nav-link').removeClass('active');
        $(`a[href="${id}"]`).addClass('active');
    }

    // Interceptar clicks
    $(document).on('click', '.spa-link, .nav-link, .navbar-brand', function(e) {
        const target = $(this).attr('href');
        if (target.startsWith('#')) {
            e.preventDefault();
            window.location.hash = target;
            render(target);
        }
    });

    // Carga inicial basada en la URL
    render(window.location.hash);
});