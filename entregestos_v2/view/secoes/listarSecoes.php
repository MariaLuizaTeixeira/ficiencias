<?php 
require_once(__DIR__ . "/../../controller/SecaoController.php");

$secao = new SecaoController();

$secoes = $secao->listar();
print "<pre>";
print_r($secoes);
print "</pre>";

?>
