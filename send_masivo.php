<?php
// Cargar la base de datos de médicos
$dataFile = 'medicos_db.json';
$medicos = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asunto = $_POST['asunto'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    // Procesar la subida de la imagen si existe
    if (isset($_FILES['imagen_mail']) && $_FILES['imagen_mail']['error'] === UPLOAD_ERR_OK) {
        $dirSubida = 'uploads/';
        if (!is_dir($dirSubida)) {
            mkdir($dirSubida, 0755, true);
        }
        $nombreImagen = time() . '_' . basename($_FILES['imagen_mail']['name']);
        move_uploaded_file($_FILES['imagen_mail']['tmp_name'], $dirSubida . $nombreImagen);
    }

    // Aquí se procesa la lista local de correos (en producción enviará vía SMTP)
    // Redirigir de vuelta al dashboard indicando que fue exitoso
    header("Location: dashboard.php?envio=exitoso");
    exit();
} else {
    // Si entran directamente por URL, regresa al dashboard
    header("Location: dashboard.php");
    exit();
}
?>