<?php
// api_tito.php - El Cerebro de Recepción

// 1. SEGURIDAD: Una clave secreta para que nadie más publique cosas falsas
$clave_secreta = "TITO_EL_DURO_2026"; 

// Recibimos los datos del Bot
$datos = json_decode(file_get_contents('php://input'), true);

// Verificamos si la clave es correcta
if (!isset($datos['clave']) || $datos['clave'] !== $clave_secreta) {
    http_response_code(403);
    die("⛔ ACCESO DENEGADO: Clave incorrecta.");
}

// 2. BASE DE DATOS SIMPLE (Archivo JSON en tu web)
$archivo_db = 'catalogo_peliculas.json';

// Si no existe, lo creamos
if (!file_exists($archivo_db)) {
    file_put_contents($archivo_db, '[]');
}

// Leemos lo que ya hay
$catalogo_actual = json_decode(file_get_contents($archivo_db), true);

// 3. AGREGAMOS LO NUEVO
$nueva_entrada = [
    'id' => uniqid(),
    'titulo' => $datos['titulo'],
    'link_telegram' => $datos['link'],
    'tipo' => $datos['tipo'], // 'pelicula' o 'serie'
    'fecha' => date('Y-m-d H:i:s')
];

// Lo ponemos al principio de la lista (lo más nuevo arriba)
array_unshift($catalogo_actual, $nueva_entrada);

// Guardamos
file_put_contents($archivo_db, json_encode($catalogo_actual, JSON_PRETTY_PRINT));

echo "✅ ÉXITO: " . $datos['titulo'] . " publicado en la web.";
?>
