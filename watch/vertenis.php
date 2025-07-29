<?php
require_once '../logic/database.php';

// Protección modificada para permitir acceso directo solo a index.php
$archivo_actual = basename(__FILE__);
if ($archivo_actual == basename($_SERVER["SCRIPT_FILENAME"]) && $archivo_actual != 'vertenis.php') {
    die("Acceso denegado.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id = intval($_GET['id']);
$producto = getProductoById($id);

if (!$producto) {
    header("Location: ../index.php");
    exit();
}

// Obtener todas las imágenes relacionadas
$ids_fotos = explode(',', $producto['ID_FOTT']);
$imagenes = getImagesByIds($ids_fotos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" href="../sketch/puchicaslogo.ico">
    <title><?php echo htmlspecialchars($producto['MODELO']); ?> -INFO</title>
    <link rel="stylesheet" href="../sketch/stylesone.css">
</head>
<body>
       
    <header>
        <h1><?php echo htmlspecialchars($producto['CATEGORIA']); ?></h1>
        <p class="marca-modelo"><?php echo htmlspecialchars($producto['MODELO']); ?></p>
    </header>
    
    <nav>
        <button onclick="window.history.back()">REGRESAR</button>
    </nav>
    
    <main class="detalle-producto">
        <div class="galeria">
            <?php foreach ($imagenes as $imagen): ?>
                <div class="imagen-container">
                    <?php if (strpos($imagen['TIPO_MIME'], 'image/') === 0): ?>
                        <img src="data:<?php echo $imagen['TIPO_MIME']; ?>;base64,<?php echo base64_encode($imagen['FOTO']); ?>" 
                             alt="<?php echo htmlspecialchars($imagen['NOMBRE']); ?>">
                    <?php elseif (strpos($imagen['TIPO_MIME'], 'video/') === 0): ?>
                        <video controls>
                            <source src="data:<?php echo $imagen['TIPO_MIME']; ?>;base64,<?php echo base64_encode($imagen['FOTO']); ?>" 
                                    type="<?php echo $imagen['TIPO_MIME']; ?>">
                            Tu navegador no soporta el elemento de video.
                        </video>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="info-producto">
            <h2>INFORMACION DEL PRODUCTO SELECCIONADO</h2>
            <p><strong>CODIGO:</strong> <?php echo htmlspecialchars($producto['UPC']); ?></p>
            <p><strong>DESCRIPCION:</strong> <?php echo htmlspecialchars($producto['DESCRIPCION']); ?></p>
            <p><strong>TIPO:</strong> <?php echo htmlspecialchars($producto['DIVISION']); ?></p>
            <p><strong>CATEGORIA:</strong> <?php echo htmlspecialchars($producto['DEPARTAMENTO']); ?></p>
            <p><strong>MARCA:</strong> <?php echo htmlspecialchars($producto['CATEGORIA']); ?></p>
            <p><strong>MODELO:</strong> <?php echo htmlspecialchars($producto['MODELO']); ?></p>
            <p><strong>COLOR:</strong> <?php echo htmlspecialchars($producto['COLOR']); ?></p>
            
            <h3>TALLA DEL PRODUCTO</h3>
            <ul class="tallas">
                <li>USS: <?php echo htmlspecialchars($producto['TALLA_USS']); ?></li>
                <li>EUR: <?php echo htmlspecialchars($producto['TALLA_EUR']); ?></li>
                <li>CM:  <?php echo htmlspecialchars($producto['TALLA_CM']); ?></li>
            </ul>
            
            <h3>COSTO DEL PRODUCTO</h3>
            <ul class="precios">
                <li>Precio: Q<?php echo number_format($producto['PRECIO'], 2); ?></li>
                <li>Oferta: Q<?php echo number_format($producto['PRECIO_OFERTA'], 2); ?></li>
            </ul>
            
            <p class="disponibilidad">
                <strong>DISPONIBILIDAD:</strong> 
                <span class="<?php echo $producto['VENDIDO'] ? 'vendido' : 'disponible'; ?>">
                    <?php echo $producto['VENDIDO'] ? 'Vendido' : 'Disponible'; ?>
                </span>
            </p>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
