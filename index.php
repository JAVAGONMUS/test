<?php
require_once 'logic/database.php';

// Protección modificada para permitir acceso directo solo a index.php
$archivo_actual = basename(__FILE__);
if ($archivo_actual == basename($_SERVER["SCRIPT_FILENAME"]) && $archivo_actual != 'index.php') {
    die("Acceso denegado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" href="sketch/puchicaslogo.ico">
	<meta name="keywords" content="PUCHICA QUE PRECIOS, puchica que precios,tenis,TENIS,CALZADO,calzado,zapatos,ZAPATOS,tenis baratos,TENIS BARATOS,zapatos baratos,ZAPATOS BARATOS,tenis guatemala, TENIS GUATEMALA,tenis buenos bonitos y baratos,TENIS BUENOS BONITOS Y BARATOS, tenis chileros en guate, TENIS CHILEROS EN GUATE">
	<meta name="description" content="PUCHICA QUE PRECIOS S.A. es una tienda en linea donde encontraras los mejores tenis a muy buen precio, tenemos variedad de diseños y tallas.">
	<meta name="author" content="PUCHICA QUE PRECIOS S.A.">
	<meta name="copyright" content="PUCHICA QUE PRECIOS S.A.">
	<meta name="robots" content="index">
	<title>CATALOGO</title>
	<link rel="stylesheet" href="sketch/stylesone.css">
</head>
<body>
    <header>
        <h1 class="TituloS1">GUATE MOSS S.A.</h1>
		<h3 class="SubtituloS1">AQUI CONSENTIMOS A TODOS NUESTROS CLIENTES</h3>
    </header>
    
    <nav>
        <button onclick="window.open('https://www.facebook.com/profile.php?id=100093685280633', '_blank')">PAGINA EN FACEBOOK</button>
        <button onclick="window.location.href='watch/existencias.php'">CATALOGO GENERAL</button>
        <button onclick="window.open('watch/newpicture.php', '_blank')">AGREGAR</button>
    </nav>
    
    <main>
        <form action="watch/disponibles.php" method="get" class="search-form">
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" placeholder="Ej. Nike, Adidas...">
            </div>
            
            <div class="form-group">
                <label for="talla">Talla:</label>
                <input type="number" id="talla" name="talla" step="0.5" min="0" placeholder="Ej. 7.5">
            </div>
            
            <button type="submit" class="btn-buscar">BUSCAR</button>
        </form>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
    
    <script src="logic/codexone.js"></script>
</body>
</html>
