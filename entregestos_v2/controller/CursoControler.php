<?php

require_once(__DIR__ . "/../dao/CursoDAO.php");

class CursoController {

    public function listar(){
        $cursoDAO = new CursoDAO();
        return $cursoDAO->list();
    }
}
