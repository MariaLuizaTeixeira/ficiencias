<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Curso.php");

class CursoDAO
{

    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    public function list()
    {
        $sql = "SELECT * FROM cursos ORDER BY nome";
        $stm = $this->conn->prepare($sql);

        $stm->execute();

        return $this->map($stm->fetchAll());
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM cursos WHERE id = ?";

        $stm = $this->conn->prepare($sql);

        $stm->execute([$id]);

        $dados = $this->map($stm->fetchAll());

        return !empty($dados) ? $dados[0] : null;
    }

    public function insert(Curso $curso)
    {
        try {
            $sql = "INSERT INTO cursos (nome, descricao) VALUES (:nome, :descricao)";

            $stm = $this->conn->prepare($sql);

            $stm->bindValue("nome", $curso->getNome());
            $stm->bindValue("descricao", $curso->getDescricao());

            $stm->execute();

            return "";
        } catch (\PDOException $e) {
            return "Erro ao salvar curso: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    public function update(Curso $curso)
    {
        try {
            $sql = "UPDATE cursos SET nome = :nome, descricao = :descricao WHERE id = :id";

            $stm = $this->conn->prepare($sql);

            $stm->bindValue("nome", $curso->getNome());
            $stm->bindValue("descricao", $curso->getDescricao());
            $stm->bindValue("id", $curso->getId());

            $stm->execute();
            return "";
        } catch (\PDOException $e) {
            return "Erro ao atualizar curso: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    public function excluir(int $id)
    {
        try {
            $sql = "DELETE FROM cursos WHERE id = ?";

            $stm = $this->conn->prepare($sql);

            $stm->execute([$id]);

            return "";
        } catch (\PDOException $e) {
            return "Erro ao excluir curso: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    private function map(array $dados)
    {
        $cursos = [];
        foreach ($dados as $d) {
            $curso = new Curso();
            $curso->setId($d['id']);
            $curso->setNome($d["nome"]);
            $curso->setDescricao($d["descricao"]);
            $cursos[] = $curso;
        }
        return $cursos;
    }
}
