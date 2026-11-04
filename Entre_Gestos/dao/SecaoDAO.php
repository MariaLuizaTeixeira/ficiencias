<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Secao.php");

class SecaoDAO
{

    private PDO $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    public function list()
    {
        $sql = "SELECT s.*, c.nome as curso_nome FROM secoes s JOIN cursos c ON s.curso_id = c.id ORDER BY s.curso_id, s.ordem";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $this->map($stm->fetchAll());
    }

    public function findById(int $id)
    {
        $sql = "SELECT s.*, c.nome as curso_nome FROM secoes s JOIN cursos c ON s.curso_id = c.id WHERE s.id = ?";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id]);
        $dados = $this->map($stm->fetchAll());
        return !empty($dados) ? $dados[0] : null;
    }

    public function insert(Secao $secao)
    {
        try {
            $sql = "INSERT INTO secoes (curso_id, titulo, ordem, descricao) VALUES (:curso_id, :titulo, :ordem, :descricao)";

            $stm = $this->conn->prepare($sql);

            $stm->bindValue("curso_id", $secao->getCursoId());
            $stm->bindValue("titulo", $secao->getTitulo());
            $stm->bindValue("ordem", $secao->getOrdem());
            $stm->bindValue("descricao", $secao->getDescricao());
            $stm->execute();
            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    public function update(Secao $secao)
    {
        try {
            $sql = "UPDATE secoes SET curso_id = :curso_id, titulo = :titulo, ordem = :ordem, descricao = :descricao WHERE id = :id";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue("curso_id", $secao->getCursoId());
            $stm->bindValue("titulo", $secao->getTitulo());
            $stm->bindValue("ordem", $secao->getOrdem());
            $stm->bindValue("descricao", $secao->getDescricao());
            $stm->bindValue("id", $secao->getId());

            $stm->execute();

            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    public function excluir(int $id)
    {
        try {
            $sql = "DELETE FROM secoes WHERE id = ?";
            $stm = $this->conn->prepare($sql);
            $stm->execute([$id]);
            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    private function map(array $dados)
    {
        $secoes = [];
        foreach ($dados as $d) {
            $secao = new Secao();
            $secao->setId($d['id']);
            $secao->setCursoId($d['curso_id']);
            $secao->setTitulo($d["titulo"]);
            $secao->setOrdem($d["ordem"]);
            $secao->setDescricao($d["descricao"]);
            // Propriedade dinâmica para guardar o nome do curso só para a view
            $secao->curso_nome = $d['curso_nome'] ?? null;
            $secoes[] = $secao;
        }
        return $secoes;
    }
}
