<?php
$token          = "8587279625:AAEtY27mevA0_2BWVoZLXyYeeiWUiI7Njes";
$chat_id        = "7655000874";
$webhook_secret = substr(hash('sha256', $token . $chat_id), 0, 32);
