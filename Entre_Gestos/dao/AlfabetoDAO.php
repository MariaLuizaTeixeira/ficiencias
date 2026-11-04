<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Alfabeto.php");

class AlfabetoDAO
{

    // =========================================================
    // LISTAR
    // =========================================================
    public function list()
    {
        $sql = "SELECT * FROM alfabeto ORDER BY ordem";

        $conn = Connection::getConnection();

        $stm = $conn->prepare($sql);
        $stm->execute();

        $dados = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $this->map($dados);
    }


    // =========================================================
    // SALVAR CANAL (guarda o nome/link do canal do vídeo para não buscar de novo)
    // =========================================================
    public function salvarCanal(int $id, string $nome, string $url): void
    {
        try {
            $conn = Connection::getConnection();
            $conn->prepare("UPDATE alfabeto SET canal_nome = ?, canal_url = ? WHERE id = ?")
                 ->execute([$nome, $url, $id]);
        } catch (PDOException $e) {
            // colunas ainda não criadas (migração não rodou): só não guarda
        }
    }


    // =========================================================
    // MAPEAR
    // =========================================================
    private function map(array $dados)
    {
        $letras = array();

        foreach ($dados as $d) {

            $letra = new Alfabeto();

            $letra->setId($d["id"]);
            $letra->setLetra($d["letra"]);
            $letra->setVideoId($d["video_id"]);
            $letra->setOrdem($d["ordem"]);
            $letra->setCanalNome($d["canal_nome"] ?? null);
            $letra->setCanalUrl($d["canal_url"] ?? null);

            array_push($letras, $letra);
        }

        return $letras;
    }
}