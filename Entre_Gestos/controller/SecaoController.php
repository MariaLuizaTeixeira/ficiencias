<?php
require_once(__DIR__ . "/../dao/SecaoDAO.php");
require_once(__DIR__ . "/../dao/CursoDAO.php");
require_once(__DIR__ . "/../model/Secao.php");

class SecaoController
{

    private SecaoDAO $dao;
    private CursoDAO $cursoDao;

    public function __construct()
    {
        $this->dao = new SecaoDAO();
        $this->cursoDao = new CursoDAO();
    }

    public function listar()
    {
        return $this->dao->list();
    }
    public function listarCursos()
    {
        return $this->cursoDao->list();
    }
    public function buscarPorId(int $id)
    {
        return $this->dao->findById($id);
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $secao = new Secao();
            if (!empty($_POST['id'])) $secao->setId($_POST['id']);
            $secao->setCursoId($_POST['curso_id']);
            $secao->setTitulo($_POST['titulo']);
            $secao->setOrdem($_POST['ordem']);
            $secao->setDescricao($_POST['descricao']);

            $erro = empty($secao->getId()) ? $this->dao->insert($secao) : $this->dao->update($secao);
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
