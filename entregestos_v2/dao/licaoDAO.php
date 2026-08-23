<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Licao.php");

class LicaoDAO
{
    public function list()
    {
        $sql = "SELECT * FROM licoes";

        $conn = Connection::getConnection();

        $stm = $conn->prepare($sql);
        $stm->execute();

        $dados = $stm->fetchAll();

        return $this->map($dados);
    }

    private function map(array $dados)
    {
        $licoes = array();

        foreach ($dados as $d) {

            $licao = new Licao();

            $licao->setId($d["id"]);
            $licao->setSecaoId($d["secao_id"]);
            $licao->setTitulo($d["titulo"]);
            $licao->setPosicao($d["posicao"]);
            $licao->setEstaBloqueada($d["esta_bloqueada"]);

            array_push($licoes, $licao);
        }

        return $licoes;
    }
}