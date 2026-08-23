<?php

require_once(__DIR__ . "/../dao/SecaoDAO.php");

class SecaoController {

    public function listar(){
        $secaoDAO = new SecaoDAO();
        return $secaoDAO->list();
    }
}