// scripts.js - Funcionalidad adicional con JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario de búsqueda
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const marca = document.getElementById('marca').value.trim();
            const talla = document.getElementById('talla').value.trim();
            
            if (!marca && !talla) {
                e.preventDefault();
                alert('Por favor ingresa al menos un criterio de búsqueda (marca o talla)');
            }
        });
    }
    
    // Protección básica contra modificaciones
    console.log('Protección básica activada:');
    console.log('1. Validación de formularios del lado del cliente');
    console.log('2. Uso de consultas preparadas en PHP');
    console.log('3. Escapado de salida con htmlspecialchars');
    console.log('4. Validación de tipos de archivo en newpicture.php');
    
    // Deshabilitar clic derecho e inspección para dificultar modificaciones
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    
    document.addEventListener('keydown', function(e) {
        // Deshabilitar F12, Ctrl+Shift+I, Ctrl+Shift+C, Ctrl+U
        if (e.key === 'F12' || 
            (e.ctrlKey && e.shiftKey && e.key === 'I') || 
            (e.ctrlKey && e.shiftKey && e.key === 'C') || 
            (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
        }
    });
});

// Función para mostrar mensajes de éxito/error
function showMessage(type, message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `mensaje ${type}`;
    messageDiv.textContent = message;
    
    const main = document.querySelector('main');
    if (main) {
        main.insertBefore(messageDiv, main.firstChild);
        
        // Eliminar el mensaje después de 5 segundos
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
}

// Lightbox para imágenes y videos
document.addEventListener('DOMContentLoaded', function() {
    // Crear elementos del lightbox
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <span class="close-lightbox">&times;</span>
        <div class="lightbox-media-container"></div>
    `;
    document.body.appendChild(lightbox);
    
    // Funcionalidad del lightbox
    function openLightbox(content) {
        const container = lightbox.querySelector('.lightbox-media-container');
        container.innerHTML = '';
        container.appendChild(content);
        lightbox.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Evitar scroll
    }

        
    let currentMediaIndex = 0;
    let mediaItems = [];

    function initLightboxNavigation() {
        mediaItems = Array.from(document.querySelectorAll('.img-preview, .video-thumbnail'));
        
        document.addEventListener('keydown', function(e) {
            if (lightbox.style.display !== 'block') return;
            
            if (e.key === 'ArrowLeft') {
                navigateLightbox(-1); // Anterior
            } else if (e.key === 'ArrowRight') {
                navigateLightbox(1); // Siguiente
            }
        });
        
        // Agregar botones de navegación
        const navHTML = `
            <button class="lightbox-nav prev">&lt;</button>
            <button class="lightbox-nav next">&gt;</button>
        `;
        lightbox.querySelector('.lightbox-media-container').insertAdjacentHTML('beforeend', navHTML);
        
        lightbox.querySelector('.prev').addEventListener('click', () => navigateLightbox(-1));
        lightbox.querySelector('.next').addEventListener('click', () => navigateLightbox(1));
    }

    function navigateLightbox(direction) {
        currentMediaIndex += direction;
        
        // Circular navigation
        if (currentMediaIndex >= mediaItems.length) currentMediaIndex = 0;
        if (currentMediaIndex < 0) currentMediaIndex = mediaItems.length - 1;
        
        const mediaItem = mediaItems[currentMediaIndex];
        const isVideo = mediaItem.classList.contains('video-thumbnail');
        const mediaElement = isVideo 
            ? document.createElement('video') 
            : document.createElement('img');
        
        mediaElement.src = mediaItem.src || mediaItem.querySelector('source').src;
        mediaElement.controls = isVideo;
        mediaElement.className = isVideo ? 'lightbox-video' : 'lightbox-content';
        mediaElement.autoplay = isVideo;
        
        openLightbox(mediaElement);
    }
    
    function closeLightbox() {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Eventos
    lightbox.querySelector('.close-lightbox').addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeLightbox();
    });
    
    // Escapar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
    
    // Asignar eventos a todas las miniaturas
    document.querySelectorAll('.img-preview, .video-thumbnail').forEach((element, index) => {
        element.addEventListener('click', function(e) {
        e.preventDefault();
        currentMediaIndex = index;
            
            const isVideo = this.classList.contains('video-thumbnail');
            const mediaElement = isVideo 
                ? document.createElement('video') 
                : document.createElement('img');
                
            mediaElement.src = this.src || this.querySelector('source').src;
            mediaElement.controls = isVideo;
            mediaElement.className = isVideo ? 'lightbox-video' : 'lightbox-content';
            mediaElement.autoplay = isVideo;
            
            openLightbox(mediaElement);
        });
    });

    // Inicializar al cargar
    initLightboxNavigation();

});

// Ajustar elementos según el tamaño de pantalla
function adjustForScreenSize() {
    const screenWidth = window.innerWidth;
    
    // Ejemplo: Cambiar comportamiento de botones en móviles
    if (screenWidth < 768) {
        // Ajustes específicos para móviles
        document.querySelectorAll('button').forEach(button => {
            button.style.fontSize = '14px';
        });
    } else {
        // Restaurar estilos para desktop
        document.querySelectorAll('button').forEach(button => {
            button.style.fontSize = '';
        });
    }
}

// Ejecutar al cargar y al cambiar tamaño de pantalla
window.addEventListener('load', adjustForScreenSize);
window.addEventListener('resize', adjustForScreenSize);
