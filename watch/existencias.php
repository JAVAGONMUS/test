<?php
require_once '../logic/database.php';

// Protección modificada para permitir acceso directo solo a index.php
$archivo_actual = basename(__FILE__);
if ($archivo_actual == basename($_SERVER["SCRIPT_FILENAME"]) && $archivo_actual != 'existencias.php') {
    die("Acceso denegado.");
}

$productos = getAllProductos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" href="../sketch/puchicaslogo.ico">
    <title>EXISTENCIAS</title>
    <link rel="stylesheet" href="../sketch/stylesone.css">
</head>
<body>
    <header>
        <h1>EXISTENCIAS DE TODO EL CALZADO</h1>
    </header>
    
    <nav>
        <button onclick="window.location.href='../index.php'">REGRESAR A INICIO</button>
    </nav>
    
    <main>
        <table class="tabla-existencias">
            <thead>
                <tr>
                    <th>IMAGEN</th>
                    <th>UPC</th>
                    <th>PRECIO</th>
                    <th>DISPONIBILIDAD</th>
                    <th>ESTADO</th>
                    <th>PRODUCTO</th>
                    <th>CATEGORIA</th>
                    <th>MARCA</th>
                    <th>MODELO</th>
                    <th>TALLA USS</th>
                    <th>TALLA EUR</th>
                    <th>TALLA CM</th>
                    <th>INFORMACION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td>
                        <?php 
                            $imagenes = getImagesByIds([$producto['ID_FOT']]);
                            if (!empty($imagenes)) {
                                $imagen = $imagenes[0];
                                $src = 'data:' . $imagen['TIPO_MIME'] . ';base64,' . base64_encode($imagen['FOTO']);
                                
                                if (strpos($imagen['TIPO_MIME'], 'video/') === 0) {
                                    echo '<video class="video-thumbnail responsive-media" width="80" height="80" muted loop>';
                                    echo '<source src="' . $src . '" type="' . $imagen['TIPO_MIME'] . '">';
                                    echo '</video>';
                                } else {
                                    echo '<img src="' . $src . '" alt="' . htmlspecialchars($producto['MODELO']) . '" class="img-preview responsive-media">';
                                }
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($producto['UPC']); ?></td>
                    <td>$<?php echo number_format($producto['PRECIO_OFERTA'], 2); ?></td>
                    <td><?php echo $producto['VENDIDO'] ? 'VENDIDO' : 'DISPONIBLE'; ?></td>
                    <td><?php echo htmlspecialchars($producto['ESTADO']); ?></td>
                    <td><?php echo htmlspecialchars($producto['DIVISION']); ?></td>
                    <td><?php echo htmlspecialchars($producto['DEPARTAMENTO']); ?></td>
                    <td><?php echo htmlspecialchars($producto['CATEGORIA']); ?></td>
                    <td><?php echo htmlspecialchars($producto['MODELO']); ?></td>
                    <td><?php echo htmlspecialchars($producto['TALLA_USS']); ?></td>
                    <td><?php echo htmlspecialchars($producto['TALLA_EUR']); ?></td>
                    <td><?php echo htmlspecialchars($producto['TALLA_CM']); ?></td>
                    <td>
                        <button onclick="window.location.href='vertenis.php?id=<?php echo $producto['ID_CATT']; ?>'">
                            VER PRODUCTO
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
