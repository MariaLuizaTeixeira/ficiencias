<?php

require_once(__DIR__ . "/AuthController.php");

require_once(__DIR__ . "/../model/Licao.php");

require_once(__DIR__ . "/../dao/SecaoDAO.php");
require_once(__DIR__ . "/../dao/LicaoDAO.php");
require_once(__DIR__ . "/../dao/QuestaoDAO.php");
require_once(__DIR__ . "/../dao/ProgressoDAO.php");

class LicaoController
{
    private LicaoDAO  $dao;
    private SecaoDAO  $secaoDao;
    private QuestaoDAO $questaoDao;

    public function __construct()
    {
        $this->dao        = new LicaoDAO();
        $this->secaoDao   = new SecaoDAO();
        $this->questaoDao = new QuestaoDAO();
    }

    public function listar(): array
    {
        return $this->dao->list();
    }

    public function listarSecoes(): array
    {
        return $this->secaoDao->list();
    }

    public function buscarPorId(int $id): ?Licao
    {
        return $this->dao->findById($id);
    }




    public function salvar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $licao = new Licao();
            if (!empty($_POST['id'])) $licao->setId($_POST['id']);
            $licao->setSecaoId($_POST['secao_id']);
            $licao->setTitulo($_POST['titulo']);
            $licao->setOrdem($_POST['ordem']);
            $licao->setEstaBloqueada(isset($_POST['esta_bloqueada']));

            $erro = empty($licao->getId()) ? $this->dao->insert($licao) : $this->dao->update($licao);
            if (empty($erro)) {
                header("Location: listar.php");
                exit;
            } else {
                echo "<p>Erro: $erro</p>";
            }
        }
    }

    public function excluir(): void
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

    /**
     * Retorna todas as questões da lição, incluindo suas opções (se múltipla escolha).
     */
    public function buscarTodasQuestoes(int $licaoId): array
    {
        $questoes = $this->questaoDao->findAllByLicao($licaoId);
        $resultado = [];

        foreach ($questoes as $questao) {
            $item = ['questao' => $questao, 'opcoes' => []];
            if ($questao->getTipo() === 'multipla_escolha') {
                $item['opcoes'] = $this->questaoDao->findOpcoesByQuestao($questao->getId());
            } elseif ($questao->getTipo() === 'associacao') {
                $item['pares'] = $this->questaoDao->findParesByQuestao($questao->getId());
            } elseif ($questao->getTipo() === 'aberta') {
                $item['resposta_correta'] = $this->questaoDao->findRespostaAberta($questao->getId());
            }
            $resultado[] = $item;
        }

        return $resultado;
    }

    /**
     * Conclui a lição e salva o progresso do aluno.
     */
    public function concluir(int $licaoId): bool
    {
        $auth = new AuthController();
        $alunoId = $auth->getUsuarioLogadoId();

        if (!$alunoId) return false;

        $progressoDao = new ProgressoDAO();
        $progressoDao->concluirLicao($alunoId, $licaoId);

        return true;
    }
}
