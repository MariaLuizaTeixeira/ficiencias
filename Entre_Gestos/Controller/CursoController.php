<?php
require_once(__DIR__ . "/../dao/CursoDAO.php");
require_once(__DIR__ . "/../model/Curso.php");

class CursoController
{

    private CursoDAO $dao;

    public function __construct()
    {
        $this->dao = new CursoDAO();
    }

    public function listar()
    {
        return $this->dao->list();
    }

    public function buscarPorId(int $id)
    {
        return $this->dao->findById($id);
    }

    public function salvar()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $curso = new Curso();

            if (!empty($_POST['id'])) $curso->setId($_POST['id']);

            $curso->setNome($_POST['nome']);
            $curso->setDescricao($_POST['descricao']);

            $erro = empty($curso->getId()) ? $this->dao->insert($curso) : $this->dao->update($curso);

            if (empty($erro)) {
                header("Location: listar.php");
                exit;
            } else {
                echo "<p>Erro: $erro</p>";
            }
        }
    }

    public function excluir()
    {
        if (isset($_GET['id'])) {
            $erro = $this->dao->excluir($_GET['id']);
            if (empty($erro)) {
                header("Location: listar.php");
                exit;
            } else {
                echo "<p>Erro: $erro</p>";
            }
        }
    }
}
