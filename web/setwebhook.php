<?php
include("settings.php");

$webhook_url = (isset($_SERVER['HTTPS']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST']
    . dirname($_SERVER['PHP_SELF']) . '/bot.php';

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px;white-space:pre-wrap">';

// === 1. Verificar token con getMe ===
echo "▶ PASO 1: Verificando token...\n";
$me = tg_request('getMe', []);
echo $me . "\n\n";
$me_data = json_decode($me, true);
if (empty($me_data['ok'])) {
    echo "❌ TOKEN INVÁLIDO. Revisa settings.php\n";
    echo '</pre>'; exit;
}
echo "✅ Bot: @" . $me_data['result']['username'] . "\n\n";

// === 2. Enviar mensaje de prueba ===
echo "▶ PASO 2: Enviando mensaje de prueba al chat $chat_id...\n";
$test = tg_request('sendMessage', [
    'chat_id' => $chat_id,
    'text'    => "✅ PRUEBA - " . date('Y-m-d H:i:s') . "\nSi ves este mensaje, el bot funciona correctamente.",
]);
echo $test . "\n\n";
$test_data = json_decode($test, true);
if (empty($test_data['ok'])) {
    echo "❌ NO SE PUDO ENVIAR. Razones comunes:\n";
    echo "   - Nunca presionaste /start al bot desde el chat $chat_id\n";
    echo "   - El chat_id es incorrecto\n";
    echo "   - El bot fue bloqueado\n";
    echo "\nSOLUCIÓN: Abre Telegram, busca tu bot, presiona /start, y ejecuta este script de nuevo.\n";
    echo '</pre>'; exit;
}
echo "✅ Mensaje enviado correctamente\n\n";

// === 3. Registrar webhook ===
echo "▶ PASO 3: Registrando webhook en $webhook_url...\n";
$wh = tg_request('setWebhook', [
    'url'          => $webhook_url,
    'secret_token' => $webhook_secret,
]);
echo $wh . "\n\n";

// === 4. Confirmar webhook ===
echo "▶ PASO 4: Confirmando estado del webhook...\n";
$info = tg_request('getWebhookInfo', []);
echo $info . "\n\n";

echo "════════════════════════════════════════\n";
echo "✅ TODO LISTO. Ahora prueba el login.\n";
echo "⚠️  ELIMINA este archivo después de usarlo.\n";
echo '</pre>';
