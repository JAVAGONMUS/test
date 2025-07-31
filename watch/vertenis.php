<?php
require_once '../logic/database.php';

// Protección modificada para permitir acceso directo solo a index.php
$archivo_actual = basename(__FILE__);
if ($archivo_actual == basename($_SERVER["SCRIPT_FILENAME"]) && $archivo_actual != 'vertenis.php') {
    die("Acceso denegado.");
}

// Validación de seguridad
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("HTTP/1.1 400 Solicitud incorrecta");
    die("ID de producto no válido");
}

$id = intval($_GET['id']);
$producto = getProductoById($id);

if (!$producto) {
    header("HTTP/1.1 404 No encontrado");
    die("Producto no encontrado");
}

// Obtener multimedia relacionado
$ids_fotos = explode(',', $producto['ID_FOTT']);
$imagenes = getImagesByIds($ids_fotos);

// Función para determinar el tipo de contenido
function obtenerTipoContenido($imagen) {
    if (!empty($imagen['URL_VIDEO']) && $imagen['URL_VIDEO'] !== '-') {
        return 'youtube';
    } elseif (strpos($imagen['TIPO_MIME'], 'image/') === 0) {
        return 'imagen';
    } elseif (strpos($imagen['TIPO_MIME'], 'video/') === 0) {
        return 'video';
    }
    return 'desconocido';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../sketch/puchicaslogo.ico">
    <title><?php echo htmlspecialchars($producto['MODELO']); ?> -INFO</title>
    <link rel="stylesheet" href="../sketch/stylesone.css">
    <meta name="description" content="Detalles del producto <?php echo htmlspecialchars($producto['MODELO']); ?>">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($producto['MODELO']); ?></h1>
        <p class="marca-modelo"><?php echo htmlspecialchars($producto['CATEGORIA']); ?></p>
    </header>
    
    <nav>
        <button onclick="window.history.back()">← Volver al listado</button>
        <button onclick="window.location.href='../index.php'">Ir al Inicio</button>
    </nav>
    
    <main class="detalle-producto">
        <div class="galeria">
            <?php if (empty($imagenes)): ?>
                <div class="mensaje info">Este producto no tiene imágenes/videos asociados</div>
            <?php else: ?>
                <?php foreach ($imagenes as $imagen): ?>
                    <?php $tipo = obtenerTipoContenido($imagen); ?>
                    
                    <div class="contenedor-multimedia">
                        <?php if ($tipo === 'youtube'): ?>
                            <!-- Video de YouTube -->
                            <div class="video-container">
                                <iframe src="<?php echo htmlspecialchars($imagen['URL_VIDEO']); ?>?rel=0&modestbranding=1" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen
                                        title="Video de YouTube: <?php echo htmlspecialchars($imagen['NOMBRE']); ?>">
                                </iframe>
                            </div>
                            <p class="leyenda">Video: <?php echo htmlspecialchars($imagen['NOMBRE']); ?></p>
                            
                        <?php elseif ($tipo === 'imagen'): ?>
                            <!-- Imagen normal -->
                            <img src="data:<?php echo $imagen['TIPO_MIME']; ?>;base64,<?php echo base64_encode($imagen['FOTO']); ?>" 
                                 alt="<?php echo htmlspecialchars($imagen['NOMBRE']); ?>"
                                 class="img-preview"
                                 loading="lazy">
                                 
                        <?php elseif ($tipo === 'video'): ?>
                            <!-- Video subido directamente -->
                            <video controls class="video-thumbnail">
                                <source src="data:<?php echo $imagen['TIPO_MIME']; ?>;base64,<?php echo base64_encode($imagen['FOTO']); ?>" 
                                        type="<?php echo $imagen['TIPO_MIME']; ?>">
                                Tu navegador no soporta este formato de video.
                            </video>
                            
                        <?php else: ?>
                            <div class="mensaje error">Formato no soportado</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="info-producto">
            <h2>Información Técnica</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">📋 Código UPC:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['UPC']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">🏷️ Modelo:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['MODELO']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">🎨 Color:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['COLOR']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">📦 Departamento:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['DEPARTAMENTO']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">👟 Talla US:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['TALLA_USS']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">👞 Talla EU:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['TALLA_EUR']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">📏 Talla CM:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['TALLA_CM']); ?></span>
                </div>
                
                <div class="info-item destacado">
                    <span class="info-label">💰 Precio:</span>
                    <span class="info-value precio">$<?php echo number_format($producto['PRECIO_OFERTA'], 2); ?></span>
                </div>
                
                <div class="info-item full-width">
                    <span class="info-label">🔄 Estado:</span>
                    <span class="info-value <?php echo $producto['VENDIDO'] ? 'vendido' : 'disponible'; ?>">
                        <?php echo $producto['VENDIDO'] ? '❌ Agotado' : '✅ Disponible'; ?>
                    </span>
                </div>
                
                <?php if (!empty($producto['DESCRIPCION'])): ?>
                <div class="info-item full-width descripcion">
                    <span class="info-label">📝 Descripción:</span>
                    <p class="info-value"><?php echo nl2br(htmlspecialchars($producto['DESCRIPCION'])); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
    
    <script src="../logic/codexone.js"></script>
</body>
</html>
