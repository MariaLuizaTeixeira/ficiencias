<?php
// Endpoint do temporizador: devolve as vidas e os segundos até a próxima. Só aceita GET.
require_once(__DIR__ . "/../../controller/AuthController.php");
require_once(__DIR__ . "/../../controller/AlunoController.php");

ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

$auth = new AuthController();
if (!$auth->estaLogado()) {
    http_response_code(401);
    echo json_encode(['ok' => false]);
    exit;
}

echo json_encode((new AlunoController())->estadoVidas((int) $auth->getUsuarioLogadoId()));
