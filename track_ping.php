<?php
session_start();

if (!isset($_SESSION['medico_logged'])) {
    http_response_code(403);
    exit();
}

// 1. ESTABLECER ZONA HORARIA BASE DEL SERVIDOR
date_default_timezone_set('America/Mexico_City');

$ahora = time();

// 2. CONFIGURACIÓN DE HORARIOS OFICIALES (Ajusta las fechas/horas reales)
// Día 1: 10 de Agosto (08:30 a 18:30)
$inicioDia1 = strtotime('2026-08-10 08:30:00');
$finDia1    = strtotime('2026-08-10 18:30:00');

// Día 2: 11 de Agosto (08:30 a 18:30)
$inicioDia2 = strtotime('2026-08-11 08:30:00');
$finDia2    = strtotime('2026-08-11 18:30:00');

// Verificar si la conexión está dentro de la ventana oficial
$enHorarioValido = ($ahora >= $inicioDia1 && $ahora <= $finDia1) || 
                   ($ahora >= $inicioDia2 && $ahora <= $finDia2);

if (!$enHorarioValido) {
    echo json_encode(['status' => 'fuera_de_horario']);
    exit();
}

// 3. LÍMITE MÁXIMO DE TIEMPO (CAP)
// Ejemplo: Máximo 600 minutos por día (10 horas por día = 1200 minutos en total para los 2 días)
$maxMinutosPermitidos = 1200; 

$dataFile = 'medicos_db.json';
$medicos = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$codigoMedico = $_SESSION['medico_logged'];

foreach ($medicos as &$m) {
    if ($m['codigo'] === $codigoMedico) {
        $minutosActuales = $m['minutos_online'] ?? 0;

        // Solo sumar si no ha superado el tope máximo
        if ($minutosActuales < $maxMinutosPermitidos) {
            $m['minutos_online'] = $minutosActuales + 1;
        }

        $m['ultima_conexion'] = date('H:i:s');
        break;
    }
}

file_put_contents($dataFile, json_encode($medicos, JSON_PRETTY_PRINT));
echo json_encode(['status' => 'success']);
?>