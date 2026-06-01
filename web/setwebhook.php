<?php
include("settings.php");

$webhook_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST']
    . dirname($_SERVER['PHP_SELF']) . '/webhook.php';

$result = file_get_contents(
    "https://api.telegram.org/bot{$token}/setWebhook?" . http_build_query([
        'url'          => $webhook_url,
        'secret_token' => $webhook_secret,
    ])
);

$json = json_decode($result, true);
echo '<pre style="font-family:monospace;font-size:14px">';
echo "URL registrada: $webhook_url\n";
echo "Secret:         $webhook_secret\n\n";
echo "Respuesta Telegram:\n" . json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo '</pre>';
echo '<p style="color:red;font-weight:bold">⚠️ Elimina este archivo del servidor después de usarlo.</p>';
