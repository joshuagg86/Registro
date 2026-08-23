<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Skip PAIN - Registro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-cover bg-center bg-no-repeat" style="background-image: url('img/bg.jpg');">

  <div class="bg-[#ffffff] rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden grid grid-cols-1 md:grid-cols-2">
    
    <!-- LADO IZQUIERDO: FORMULARIO -->
    <div class="p-8 flex flex-col justify-between items-center">
      
      <!-- Logo Skip PAIN -->
      <div class="mb-2 text-center">
        <img src="img/skip_pain.png" alt="Skip PAIN Logo" class="h-[130px] mx-auto object-contain">
      </div>

      <h2 class="text-slate-800 text-lg font-bold text-center mb-4">
        REGISTRO
      </h2>

      <!-- Formulario de Registro -->
      <form action="process_register.php" method="POST" class="w-full space-y-2.5">
        <input type="text" name="nombre" required placeholder="Nombre" class="w-full px-4 py-2.5 rounded-full bg-slate-200 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
        
        <input type="text" name="apellidos" required placeholder="Apellidos" class="w-full px-4 py-2.5 rounded-full bg-slate-200 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
        
        <input type="tel" name="telefono" required placeholder="Teléfono celular" class="w-full px-4 py-2.5 rounded-full bg-slate-200 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
        
        <input type="email" name="correo" required placeholder="Correo electrónico" class="w-full px-4 py-2.5 rounded-full bg-slate-200 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
        
        <input type="text" name="especialidad" required placeholder="Especialidad" class="w-full px-4 py-2.5 rounded-full bg-slate-200 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">

        <button type="submit" class="w-full py-3 bg-[#0b3c3d] hover:bg-teal-900 text-white font-bold rounded-full text-sm transition mt-2 shadow-md">
          Registrarse
        </button>
      </form>

      <!-- Botón de Acceso Directo a Transmisión -->
      <div class="mt-4 text-center w-full">
        <p class="text-xs text-slate-500 mb-1">¿Ya te habías registrado?</p>
        <a href="live.php" class="inline-block w-full py-2 px-4 border border-[#0b3c3d] text-[#0b3c3d] hover:bg-[#0b3c3d] hover:text-white rounded-full text-xs font-bold transition">
          Ingresar a la Transmisión En Vivo →
        </a>
      </div>

      <!-- Logo Menarini -->
      <div class="mt-4 text-center">
        <img src="img/menarini.png" alt="Menarini México Logo" class="h-6 mx-auto object-contain">
      </div>
    </div>

    <!-- LADO DERECHO: ILUSTRACIÓN DEL PERSONAJE -->
    <div class="bg-[#ffffff] p-6 flex items-center justify-center">
      <img src="img/hombre.jpg" alt="Anatomía Skip PAIN" class="w-full h-full object-cover rounded-2xl">
    </div>

  </div>

</body>
</html>