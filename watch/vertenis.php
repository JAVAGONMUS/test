<?php
require_once '../logic/database.php';

// Protección modificada para permitir acceso directo solo a index.php
$archivo_actual = basename(__FILE__);
if ($archivo_actual == basename($_SERVER["SCRIPT_FILENAME"]) && $archivo_actual != 'vertenis.php') {
    die("Acceso denegado.");
}

$EMPR = "1";

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
    if ($imagen['URL_VIDEO'] !== '-') {
        return 'youtube';
    } elseif (strpos($imagen['TIPO_MIME'], 'image/') === 0) {
        return 'imagen';
    } elseif (strpos($imagen['TIPO_MIME'], 'video/') === 0) {
        return 'video';
    }
    return 'desconocido';
}

function obtenerIdYoutube($url) {
    // Extrae el ID del video desde distintas variantes de URL
    $patron = '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
    preg_match($patron, $url, $coincidencias);
    return $coincidencias[1] ?? false;
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
                        <?php if ($tipo === 'youtube'): 

                            if (!empty($imagen['URL_VIDEO'])) {
                                $videoID = obtenerIdYoutube($imagen['URL_VIDEO']);
                                if ($videoID) {
                                    echo '<div class="contenedor-multimedia">';
                                    echo '<iframe width="300" height="200" src="https://www.youtube.com/embed/' . htmlspecialchars($videoID) . '" frameborder="0" allowfullscreen></iframe>';
                                    echo '</div>';
                                }
                            }

                        ?>    
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
                    <span class="info-label">🏷️ Marca:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['CATEGORIA']); ?></span><br>
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
                    <span class="info-value precio">Q<?php echo number_format($producto['PRECIO_OFERTA'], 2); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">📋 Código:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['UPC']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">📦 Categoria:</span>
                    <span class="info-value"><?php echo htmlspecialchars($producto['DEPARTAMENTO']); ?></span>
                </div>  
                
                <div class="info-item full-width">
                    <span class="info-label">🔄 --- </span>
                    <span class="info-value <?php echo $producto['VENDIDO'] ? 'vendido' : 'disponible'; ?>">
                        <?php echo $producto['VENDIDO'] ? '❌ VENDIDO' : '✅ DISPONIBLE'; ?>
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

    <main>
        <form class="search-form">
            <h1 >GUIA DE TALLAS EN GUATEMALA</h3>
            <h3 >(puedes medir con una regla los centimetros de tu pie)</h3>
            <table class="tabla-disponibles">
                <thead>
                    <tr>
                        <th>Guatemala</th>
                        <th>Centimetros</th>
                        <th>Usa</th>                
                    </tr>
                </thead>
                <tbody>
                    <tr>                    
                        <td>33</td>
                        <td>22.0</td>  
                        <td>3</td>                                              
                    </tr>
                    <tr>                    
                        <td>34</td>
                        <td>22.7</td> 
                        <td>4</td>                                               
                    </tr>
                    <tr>                    
                        <td>35</td>
                        <td>23.3</td>
                        <td>4.5</td>                        
                    </tr>
                    <tr>                    
                        <td>36</td>
                        <td>24.0</td> 
                        <td>5</td>                       
                    </tr>
                    <tr>                    
                        <td>37</td>
                        <td>24.7</td> 
                        <td>5.5</td>                       
                    </tr>
                    <tr>                    
                        <td>38</td>
                        <td>25.3</td>
                        <td>6.5</td>                        
                    </tr>
                    <tr>                    
                        <td>39</td>
                        <td>26.0</td> 
                        <td>7.0</td>                       
                    </tr>
                    <tr>                    
                        <td>40</td>
                        <td>26.7</td> 
                        <td>7.5</td>                       
                    </tr>
                    <tr>                    
                        <td>41</td>
                        <td>27.3</td> 
                        <td>8.5</td>                       
                    </tr>
                    <tr>                    
                        <td>42</td>
                        <td>28.0</td> 
                        <td>9.0</td>                       
                    </tr>
                    <tr>                    
                        <td>43</td>
                        <td>28.7</td> 
                        <td>10.0</td>                       
                    </tr>
                    <tr>                    
                        <td>44</td>
                        <td>29.3</td>
                        <td>10.5</td>                        
                    </tr>            
                </tbody>
            </table>
        </form>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
    
    <script src="../logic/codexone.js"></script>
</body>
</html>
