<?php
// Endpoint da tela da lição: tira 1 vida do aluno logado e responde JSON. Só aceita POST.
require_once(__DIR__ . "/../../controller/AuthController.php");
require_once(__DIR__ . "/../../controller/AlunoController.php");

// Erros/avisos do PHP em HTML quebrariam o JSON que o licao.js espera
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

$auth = new AuthController();

// exigirLogin() redireciona para uma página HTML; aqui a resposta tem que ser JSON
if (!$auth->estaLogado()) {
    http_response_code(401);
    echo json_encode(['ok' => false]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$resposta = (new AlunoController())->perderVida((int) $auth->getUsuarioLogadoId());
if (!$resposta['ok']) {
    http_response_code(500);
}
echo json_encode($resposta);
