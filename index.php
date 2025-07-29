<?php
// CONFIGURACIÓN DE CONEXIÓN
$host = 'turntable.proxy.rlwy.net:47866'; // Reemplaza con el host de tu base en Railway
$dbname = 'railway'; // Reemplaza con el nombre de tu base
$user = 'root'; // Reemplaza con tu usuario MySQL en Railway
$pass = 'JRVMHEVvCjiiYNJetQIWQQIelcrMBTcm'; // Reemplaza con tu contraseña

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h1>✅ Conexión exitosa a la base de datos.</h1>";
} catch (PDOException $e) {
    echo "<h1>❌ Error de conexión: " . $e->getMessage() . "</h1>";
}
?>
