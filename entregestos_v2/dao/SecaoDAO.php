<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Secao.php");

class SecaoDAO
{
    public function list()
    {
        $sql = "SELECT * FROM secoes";

        $conn = Connection::getConnection();

        $stm = $conn->prepare($sql);
        $stm->execute();

        $dados = $stm->fetchAll();

        return $this->map($dados);
    }

    private function map(array $dados)
    {
        $secoes = array();

        foreach ($dados as $d) {

            $secao = new Secao();

            $secao->setId($d["id"]);
            $secao->setCursoId($d["curso_id"]);
            $secao->setTitulo($d["titulo"]);
            $secao->setPosicao($d["posicao"]);
            $secao->setDescricao($d["descricao"]);

            array_push($secoes, $secao);
        }

        return $secoes;
    }
}