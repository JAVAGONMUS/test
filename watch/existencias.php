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
                    <th>MARCA</th>
                    <th>PRECIO</th>
                    <th>DISPONIBILIDAD</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr onclick="window.location.href='vertenis.php?id=<?php echo $producto['ID_CATT']; ?>'">
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
                    <td><?php echo htmlspecialchars($producto['CATEGORIA']); ?></td>    
                    <td>Q<?php echo number_format($producto['PRECIO_OFERTA'], 2); ?></td>
                    <td><?php echo $producto['VENDIDO'] ? 'VENDIDO' : 'DISPONIBLE'; ?></td>                  
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
</body>
</html>
