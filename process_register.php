<?php
session_start();

// Carpeta temporal local para guardar datos si no hay MySQL
$dataFile = 'medicos_db.json';

// Cargar o inicializar registros
$medicos = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Obtener el correo ingresado
$correoInput = strtolower(trim($_POST['correo'] ?? ''));

// VERIFICACIÓN: Si el correo ya existe, iniciar sesión y mandar a la transmisión
foreach ($medicos as $m) {
    if (strtolower($m['correo']) === $correoInput) {
        $_SESSION['medico_logged'] = $m['codigo'];
        $_SESSION['medico_nombre'] = $m['nombre'];
        
        // Redirigir directamente al live si ya estaba registrado
        header("Location: live.php");
        exit();
    }
}

// SI ES NUEVO: Generar código único correlativo (SKIP-001, SKIP-002...)
$nextNum = count($medicos) + 1;
$codigoUnico = "SKIP-" . str_pad($nextNum, 3, "0", STR_PAD_LEFT);

// Obtener datos del formulario
$nuevoMedico = [
    "codigo" => $codigoUnico,
    "nombre" => trim($_POST['nombre']) . ' ' . trim($_POST['apellidos']),
    "telefono" => trim($_POST['telefono']),
    "correo" => $correoInput,
    "especialidad" => trim($_POST['especialidad']),
    "entrada" => "-",
    "salidaComer" => "-",
    "regresoComer" => "-",
    "salidaEvento" => "-",
    "inicio_stream" => "-",
    "minutos_online" => 0,
    "ultima_conexion" => "-"
];

// Guardar registro
$medicos[] = $nuevoMedico;
file_put_contents($dataFile, json_encode($medicos, JSON_PRETTY_PRINT));

// Enviar correo de confirmación (Simulación nativa PHP)
$to = $_POST['correo'];
$subject = "Registro Exitoso - Skip PAIN";
$message = "Hola " . $_POST['nombre'] . ",\n\nTu registro al evento Skip PAIN ha sido exitoso.\nGracias por formar parte de esta experiencia.";
$headers = "From: evento@skippain.com";
@mail($to, $subject, $message, $headers);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Registro Confirmado</title>
</head>
<body class="bg-[#0b3c3d] min-h-screen flex items-center justify-center p-4 text-center">
  <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full space-y-4">
    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">✓</div>
    <h2 class="text-2xl font-bold text-slate-800 mb-2">¡Registro Completado!</h2>
    <p class="text-slate-600 text-sm">Hemos enviado un correo de confirmación a <strong class="text-slate-800"><?php echo htmlspecialchars($_POST['correo']); ?></strong>.</p>
    
    <div class="pt-4 space-y-2">
      <a href="live.php" class="block w-full py-3 bg-[#0b3c3d] text-white rounded-full text-sm font-bold hover:bg-teal-900 transition shadow-md">
        Ir a la Transmisión En Vivo →
      </a>
      <a href="index.php" class="block text-xs text-slate-400 hover:text-slate-600 underline">
        Volver al formulario
      </a>
    </div>
  </div>
</body>
</html>