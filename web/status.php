<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['usuario'])) {
    echo '{"action":"wait"}'; exit;
}

$u    = $_SESSION['usuario'];
$tipo = $_GET['tipo'] ?? 'login';

$file = "data/" . md5($u) . "_{$tipo}.json";

if (!file_exists($file)) { echo '{"action":"wait"}'; exit; }

$data = json_decode(file_get_contents($file), true);
@unlink($file);
echo json_encode($data ?: ['action' => 'wait']);
