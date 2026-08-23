<?php

require_once(__DIR__ . "/../dao/LicaoDAO.php");

class LicaoController
{
    private LicaoDAO $licaoDAO;

    public function __construct()
    {
        $this->licaoDAO = new LicaoDAO();
    }

    public function listar()
    {
        return $this->licaoDAO->list();
    }
}