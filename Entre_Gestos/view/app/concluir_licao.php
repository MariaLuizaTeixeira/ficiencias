<?php
require_once(__DIR__ . "/../../controller/AuthController.php");
$auth = new AuthController();
$auth->exigirLogin();

require_once(__DIR__ . "/../../controller/LicaoController.php");

$controller = new LicaoController();

$licaoId = $_GET['id'] ?? null;
if (!$licaoId) {
    header("Location: trilha.php");
    exit;
}

// Toda a lógica de negócio fica no Controller/DAO — a View só chama e redireciona
$controller->concluir((int) $licaoId);

header("Location: trilha.php");
exit;
