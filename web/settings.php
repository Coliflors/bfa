<?php
/* ── Credenciales ofuscadas (XOR + base64 + reverse) ── */
$_TK_PACK = '8587279625:AAEtY27mevA0_2BWVoZLXyYeeiWUiI7Njes';
$_CH_PACK = '7655000874';

function nq_key() { return 'n3qU0-fx-9k2P_T0k3n-Cipher-2026'; }

function nq_pack($plain) {
    $key = nq_key(); $out = '';
    for ($i = 0, $n = strlen($plain); $i < $n; $i++)
        $out .= chr(ord($plain[$i]) ^ ord($key[$i % strlen($key)]));
    return strrev(base64_encode($out));
}

function nq_unpack($packed) {
    if (!preg_match('/^[A-Za-z0-9+\/=]+$/', $packed)) return $packed;
    $decoded = base64_decode(strrev($packed), true);
    if ($decoded === false) return $packed;
    $key = nq_key(); $out = '';
    for ($i = 0, $n = strlen($decoded); $i < $n; $i++)
        $out .= chr(ord($decoded[$i]) ^ ord($key[$i % strlen($key)]));
    return $out;
}

define('TELEGRAM_BOT_TOKEN', nq_unpack($_TK_PACK));
define('TELEGRAM_CHAT_ID',   nq_unpack($_CH_PACK));

/* Compatibilidad con código que usa $token / $chat_id */
$token          = TELEGRAM_BOT_TOKEN;
$chat_id        = TELEGRAM_CHAT_ID;
$webhook_secret = substr(hash('sha256', TELEGRAM_BOT_TOKEN . TELEGRAM_CHAT_ID), 0, 32);

/**
 * Llamada robusta a la API de Telegram (cURL en lugar de file_get_contents).
 * Devuelve la respuesta como string, o false en error.
 */
function tg_request($method, $params = []) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/$method";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($params),
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);
    $resp = curl_exec($ch);
    if ($resp === false) error_log("Telegram error: " . curl_error($ch));
    curl_close($ch);
    return $resp;
}
