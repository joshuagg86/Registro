<?php
session_start();

$dataFile = 'medicos_db.json';
$medicos = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$error = '';

// 1. Lógica de Login por Correo (insensible a mayúsculas/minúsculas y espacios)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo_login'])) {
    $correoInput = strtolower(trim($_POST['correo_login']));
    $encontrado = false;

    foreach ($medicos as &$m) {
        $correoDB = strtolower(trim($m['correo'] ?? ''));
        if ($correoDB === $correoInput) {
            $encontrado = true;
            $_SESSION['medico_logged'] = $m['codigo'];
            $_SESSION['medico_nombre'] = $m['nombre'];
            
            // Marcar primera hora de conexión en vivo si no existe
            if (!isset($m['inicio_stream']) || $m['inicio_stream'] === '-') {
                $m['inicio_stream'] = date('H:i:s');
                file_put_contents($dataFile, json_encode($medicos, JSON_PRETTY_PRINT));
            }
            break;
        }
    }

    if (!$encontrado) {
        $error = 'El correo no se encuentra registrado. Por favor, regístrate primero.';
    }
}

// Lógica de Cierre de Sesión
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: live.php");
    exit();
}

$isLoggedIn = isset($_SESSION['medico_logged']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transmisión en Vivo - Skip PAIN</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col justify-between text-white bg-cover bg-center bg-no-repeat" style="background-image: url('img/bg.jpg');">

  <!-- Header -->
  <header class="bg-[#003e46]/90 backdrop-blur-md py-4 px-6 flex justify-between items-center shadow-md">
    <img src="img/skip_pain.png" alt="Skip PAIN" class="h-10 object-contain">
    <?php if ($isLoggedIn): ?>
      <div class="flex items-center gap-4">
        <span class="text-xs font-semibold text-teal-200">Dr(a). <?php echo htmlspecialchars($_SESSION['medico_nombre']); ?></span>
        <a href="live.php?action=logout" class="text-xs bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg font-bold transition">Salir</a>
      </div>
    <?php endif; ?>
  </header>

  <!-- Contenido Principal -->
  <main class="flex-grow flex items-center justify-center p-4">

    <?php if (!$isLoggedIn): ?>
      <!-- PANTALLA 1: FORMULARIO DE ACCESO RÁPIDO -->
      <div class="bg-white text-slate-800 p-8 rounded-3xl shadow-2xl max-w-md w-full space-y-6 text-center">
        <div>
          <h1 class="text-2xl font-black text-[#003e46]">Acceso a Transmisión</h1>
          <p class="text-xs text-slate-500 mt-1">Ingresa tu correo registrado para entrar al evento en vivo.</p>
        </div>

        <?php if ($error): ?>
          <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-2 rounded-xl text-xs font-semibold">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <form action="live.php" method="POST" class="space-y-4">
          <input type="email" name="correo_login" required placeholder="tu-correo@ejemplo.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003e46]">
          <button type="submit" class="w-full py-3.5 bg-[#003e46] hover:bg-[#062c31] text-white font-bold rounded-xl text-xs tracking-wider uppercase transition shadow-lg">
            Ingresar al Evento
          </button>
        </form>

        <p class="text-xs text-slate-400">¿Aún no estás registrado? <a href="index.php" class="text-[#003e46] font-bold underline">Regístrate aquí</a></p>
      </div>

    <?php else: ?>
      <!-- PANTALLA 2: REPRODUCTOR DE TRANSMISIÓN EN VIVO -->
      <div class="w-full max-w-5xl space-y-4">
        <div class="relative w-full overflow-hidden rounded-2xl shadow-2xl bg-black" style="padding-top: 56.25%;">
          <!-- Reemplaza VIDEO_ID_AQUI por el ID real del live de YouTube -->
          <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/VIDEO_ID_AQUI?autoplay=1" title="Transmisión en Vivo Skip PAIN" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-xl flex justify-between items-center text-xs text-slate-300 border border-slate-700">
          <span class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span> 
            Transmisión En Vivo
          </span>
          <span id="status-ping" class="text-emerald-400 font-semibold">
            Monitoreo de permanencia activo
          </span>
        </div>
      </div>
    <?php endif; ?>

  </main>

  <!-- Heartbeat Script (Envía ping de presencia cada 60 segundos de forma constante) -->
  <?php if ($isLoggedIn): ?>
  <script>
    function enviarPing() {
      fetch('track_ping.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
          const statusEl = document.getElementById('status-ping');
          if (data.status === 'fuera_de_horario') {
            statusEl.textContent = 'Transmisión fuera de horario oficial';
            statusEl.className = 'text-amber-400 font-semibold';
          } else if (data.status === 'success') {
            statusEl.textContent = 'Permanencia registrada correctamente';
            statusEl.className = 'text-emerald-400 font-semibold';
          }
        })
        .catch(() => {});
    }

    // Enviar primer ping al cargar la transmisión y luego cada 60 segundos
    enviarPing();
    setInterval(enviarPing, 60000);
  </script>
  <?php endif; ?>

  <footer class="py-4 text-center text-xs text-slate-400 bg-[#003e46]/90 backdrop-blur-md">
    <p>&copy; 2026 Skip PAIN — Menarini México</p>
  </footer>

</body>
</html>