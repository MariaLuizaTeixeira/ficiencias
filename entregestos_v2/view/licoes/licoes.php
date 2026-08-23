<?php 
require_once(__DIR__ . "/../../controller/LicaoController.php");

$licao = new LicaoController();

$licoes = $licao->listar();
print "<pre>";
print_r($licoes);
print "</pre>";

?>
