/**
 * scripts.js - Funcionalidades principales del catálogo
 * Contempla:
 * - Lightbox para imágenes/videos
 * - Validación de formularios
 * - Protección básica contra modificaciones
 * - Soporte para videos de YouTube
 */

document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // LIGHTBOX PARA IMÁGENES/VIDEOS
    // =============================================
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <span class="close-lightbox">&times;</span>
        <div class="lightbox-media-container"></div>
        <button class="lightbox-nav prev">&lt;</button>
        <button class="lightbox-nav next">&gt;</button>
    `;
    document.body.appendChild(lightbox);

    let currentMediaIndex = 0;
    let mediaItems = [];

    function openLightbox(content, index) {
        const container = lightbox.querySelector('.lightbox-media-container');
        container.innerHTML = '';
        
        // Clonamos el elemento para evitar conflictos
        const mediaClone = content.cloneNode(true);
        
        // Si es video, activamos controles y autoplay
        if (mediaClone.tagName === 'VIDEO') {
            mediaClone.controls = true;
            mediaClone.autoplay = true;
            mediaClone.className = 'lightbox-video';
        } else if (mediaClone.tagName === 'IFRAME') {
            // Para YouTube, ajustamos tamaño
            mediaClone.className = 'lightbox-video';
            mediaClone.style.width = '80%';
            mediaClone.style.height = '80%';
        } else {
            mediaClone.className = 'lightbox-content';
        }
        
        container.appendChild(mediaClone);
        lightbox.style.display = 'block';
        document.body.style.overflow = 'hidden';
        currentMediaIndex = index;
    }

    function closeLightbox() {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Pausar videos al cerrar
        const videos = lightbox.querySelectorAll('video, iframe');
        videos.forEach(video => {
            if (video.tagName === 'VIDEO') {
                video.pause();
            } else if (video.tagName === 'IFRAME') {
                // Detener videos de YouTube
                video.src = video.src; // Esto recarga el iframe
            }
        });
    }

    function navigateLightbox(direction) {
        currentMediaIndex += direction;
        
        if (currentMediaIndex >= mediaItems.length) currentMediaIndex = 0;
        if (currentMediaIndex < 0) currentMediaIndex = mediaItems.length - 1;
        
        const mediaItem = mediaItems[currentMediaIndex];
        const mediaElement = createMediaElement(mediaItem);
        openLightbox(mediaElement, currentMediaIndex);
    }

    function createMediaElement(mediaItem) {
        if (mediaItem.classList.contains('video-thumbnail')) {
            const video = document.createElement('video');
            video.src = mediaItem.querySelector('source').src;
            video.controls = true;
            return video;
        } else if (mediaItem.tagName === 'IFRAME') {
            const iframe = document.createElement('iframe');
            iframe.src = mediaItem.src;
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allowfullscreen', '');
            return iframe;
        } else {
            const img = document.createElement('img');
            img.src = mediaItem.src;
            return img;
        }
    }

    // Eventos del lightbox
    lightbox.querySelector('.close-lightbox').addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeLightbox();
    });

    lightbox.querySelector('.prev').addEventListener('click', () => navigateLightbox(-1));
    lightbox.querySelector('.next').addEventListener('click', () => navigateLightbox(1));

    // Inicializar navegación con teclado
    document.addEventListener('keydown', function(e) {
        if (lightbox.style.display === 'block') {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') navigateLightbox(-1);
            if (e.key === 'ArrowRight') navigateLightbox(1);
        }
    });

    // Asignar eventos a elementos multimedia
    function initMediaItems() {
        mediaItems = Array.from(document.querySelectorAll('.img-preview, .video-thumbnail, .contenedor-multimedia iframe'));
        
        mediaItems.forEach((element, index) => {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                currentMediaIndex = index;
                
                let mediaElement;
                if (this.classList.contains('img-preview') {
                    mediaElement = document.createElement('img');
                    mediaElement.src = this.src;
                } else if (this.classList.contains('video-thumbnail')) {
                    mediaElement = document.createElement('video');
                    mediaElement.src = this.querySelector('source').src;
                    mediaElement.controls = true;
                } else if (this.tagName === 'IFRAME') {
                    mediaElement = this.cloneNode(true);
                }
                
                openLightbox(mediaElement, index);
            });
        });
    }

    // =============================================
    // VALIDACIÓN DE FORMULARIOS
    // =============================================
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

    // Validación para newpicture.php
    const uploadForm = document.querySelector('form[enctype="multipart/form-data"]');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const fileInput = this.querySelector('input[type="file"]');
            const youtubeInput = this.querySelector('input[name="youtube_url"]');
            
            if (!fileInput.files.length && !youtubeInput.value.trim()) {
                e.preventDefault();
                alert('Debes seleccionar un archivo o ingresar un enlace de YouTube');
            }
        });
    }

    // =============================================
    // PROTECCIÓN BÁSICA Y UTILIDADES
    // =============================================
    // Deshabilitar clic derecho e inspección
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

    // Ajustar elementos según tamaño de pantalla
    function adjustForScreenSize() {
        const screenWidth = window.innerWidth;
        
        if (screenWidth < 768) {
            // Ajustes específicos para móviles
            document.querySelectorAll('button').forEach(button => {
                button.style.fontSize = '14px';
            });
        }
    }

    // Mostrar mensajes temporales
    function showMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `mensaje ${type}`;
        messageDiv.textContent = message;
        
        const main = document.querySelector('main');
        if (main) {
            main.insertBefore(messageDiv, main.firstChild);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    }

    // =============================================
    // INICIALIZACIÓN
    // =============================================
    initMediaItems();
    adjustForScreenSize();
    window.addEventListener('resize', adjustForScreenSize);

    console.log('Scripts cargados correctamente');
});
