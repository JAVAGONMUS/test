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
        <h1 class="TituloS1">PUCHICA QUE PRECIOS S.A.</h1>
		<h3 class="SubtituloS1">TUS SNEAKERS FAVORITOS EN UN SOLO LUGAR</h3>
    </header>
    
    <nav>
		<button id="btnWhatsApp">💬 ESCRIBENOS</button>
        <button onclick="window.open('https://www.facebook.com/share/1GrRjVk5LE/', '_blank')">PAGINA EN FACEBOOK</button>
        <button onclick="window.location.href='watch/existencias.php'">CATALOGO GENERAL</button>
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

	<main>
        <form class="search-form">
        <h1 >GUIA DE TALLAS EN GUATEMALA</h3>
        <h3 >(puedes medir con una regla los centimetros de tu pie)</h3>
        <table class="tabla-disponibles">
                <thead>
                    <tr>
                        <th>Numeración en Guatemala</th>
                        <th>Numeración en USA</th>
                        <th>Numeración en Centimetros</th>                
                    </tr>
                </thead>
                <tbody>
                    <tr>                    
                        <td>33</td>
                        <td>3</td>
                        <td>22.0</td>                        
                    </tr>
                    <tr>                    
                        <td>34</td>
                        <td>4</td>
                        <td>22.7</td>                        
                    </tr>
                    <tr>                    
                        <td>35</td>
                        <td>4.5</td>
                        <td>23.3</td>                        
                    </tr>
                    <tr>                    
                        <td>36</td>
                        <td>5</td>
                        <td>24.0</td>                        
                    </tr>
                    <tr>                    
                        <td>37</td>
                        <td>5.5</td>
                        <td>24.7</td>                        
                    </tr>
                    <tr>                    
                        <td>38</td>
                        <td>6.5</td>
                        <td>25.3</td>                        
                    </tr>
                    <tr>                    
                        <td>39</td>
                        <td>7.0</td>
                        <td>26.0</td>                        
                    </tr>
                    <tr>                    
                        <td>40</td>
                        <td>7.5</td>
                        <td>26.7</td>                        
                    </tr>
                    <tr>                    
                        <td>41</td>
                        <td>8.5</td>
                        <td>27.3</td>                        
                    </tr>
                    <tr>                    
                        <td>42</td>
                        <td>9.0</td>
                        <td>28.0</td>                        
                    </tr>
                    <tr>                    
                        <td>43</td>
                        <td>10.0</td>
                        <td>28.7</td>                        
                    </tr>
                    <tr>                    
                        <td>44</td>
                        <td>10.5</td>
                        <td>29.3</td>                        
                    </tr>
                    
                </tbody>
            </table>
        </form>
    </main>
        
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
    
    <script src="logic/codexone.js"></script>
</body>
</html>
