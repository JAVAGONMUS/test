<?php
require_once '../logic/database.php';

// Protección modificada para permitir acceso directo solo a index.php
$archivo_actual = basename(__FILE__);
if ($archivo_actual == basename($_SERVER["SCRIPT_FILENAME"]) && $archivo_actual != 'newpicture.php') {
    die("Acceso denegado.");
}

// Aumentar límites para archivos grandes
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_execution_time', 300);
ini_set('max_input_time', 300);

// Configuración de la base de datos para archivos grandes
$conn = getDBConnection();
$conn->exec("SET GLOBAL max_allowed_packet=52428800"); // 50MB
$conn->exec("SET GLOBAL wait_timeout=600");
$conn->exec("SET GLOBAL interactive_timeout=600");

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre = $_FILES['imagen']['name'];
        $tipo_mime = $_FILES['imagen']['type'];
        $tamanio = $_FILES['imagen']['size'];
        $temp_path = $_FILES['imagen']['tmp_name'];
        $user_new_data = 0;

        // Validar tipo de archivo (ampliado para videos)
        $tipos_permitidos = [
            'image/jpeg', 'image/png', 'image/gif',
            'video/mp4', 'video/quicktime', 'video/x-msvideo',
            'video/x-flv', 'video/webm', 'video/3gpp'
        ];
        
        if (!in_array($tipo_mime, $tipos_permitidos)) {
            $error = "Tipo de archivo no permitido. Formatos aceptados: JPEG, PNG, GIF, MP4, MOV, AVI, FLV, WEBM, 3GPP.";
        } elseif ($tamanio > 50 * 1024 * 1024) { // 50MB máximo
            $error = "El archivo es demasiado grande. El tamaño máximo permitido es 50MB.";
        } else {
            // Leer el contenido en bloques para archivos grandes
            try {
                $contenido = file_get_contents($temp_path);
                if ($contenido === false) {
                    throw new Exception("No se pudo leer el archivo subido.");
                }
                
                // Dividir en partes si es muy grande (opcional para videos muy grandes)
                $partes = str_split($contenido, 1024 * 1024); // 1MB por parte
                
                // Iniciar transacción
                $conn->beginTransaction();
                
                try {
                    // Insertar primera parte
                    $sql = "INSERT INTO FOTOS (NOMBRE, FOTO, TIPO_MIME, FECHA_ALTA, HORA_ALTA, USER_NEW_DATA) 
                            VALUES (?, ?, ?, CURDATE(), CURTIME(), ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$nombre, $partes[0], $tipo_mime, $user_new_data]);
                    $id = $conn->lastInsertId();
                    
                    // Si hay más partes, actualizar el registro
                    if (count($partes) > 1) {
                        for ($i = 1; $i < count($partes); $i++) {
                            $sql = "UPDATE FOTOS SET FOTO = CONCAT(FOTO, ?) WHERE ID_FOT = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$partes[$i], $id]);
                        }
                    }
                    
                    $conn->commit();
                    $mensaje = "Archivo subido correctamente con ID: $id";
                } catch (Exception $e) {
                    $conn->rollBack();
                    throw $e;
                }
            } catch (Exception $e) {
                $error = "Error al procesar el archivo: " . $e->getMessage();
            }
        }
    } else {
        $error_code = $_FILES['imagen']['error'] ?? 'Desconocido';
        $error = "Error al subir el archivo. Código de error: $error_code";
    }
}

$next_id = getNextFotoId();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" href="../sketch/puchicaslogo.ico">
    <title>NEW-PHOTO</title>
    <link rel="stylesheet" href="../sketch/stylesone.css">
</head>
<body>
    <header>
        <h1>Agregar Nueva Imagen/Video</h1>
    </header>
    
    <main class="form-container">
        <?php if ($mensaje): ?>
            <div class="mensaje exito"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="mensaje error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="newpicture.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="next_id">Próximo ID:</label>
                <input type="text" id="next_id" value="<?php echo $next_id; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="imagen">Seleccionar Imagen/Video:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*,video/*" required>
                <small>Acepta imágenes (JPEG, PNG, GIF) y videos (MP4, MOV). Máximo 10MB.</small>
            </div>
            
            <button type="submit" class="btn-submit">CARGAR ARCHIVO</button>
        </form>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Catálogo de Calzado. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
