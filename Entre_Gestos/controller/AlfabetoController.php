<?php

require_once(__DIR__ . "/../dao/AlfabetoDAO.php");
require_once(__DIR__ . "/../util/youtube.php");

class AlfabetoController
{
    public function listar()
    {
        $dao = new AlfabetoDAO();
        $letras = $dao->list();
        youtube_completar_canais($letras, $dao); // garante nome/link do canal em cada letra
        return $letras;
    }
}
