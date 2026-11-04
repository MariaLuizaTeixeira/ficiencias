<?php

require_once(__DIR__ . "/../dao/AtividadeDAO.php");
require_once(__DIR__ . "/../dao/LicaoDAO.php");

class AtividadeController
{
    private AtividadeDAO $dao;
    private LicaoDAO     $licaoDao;

    public function __construct()
    {
        $this->dao      = new AtividadeDAO();
        $this->licaoDao = new LicaoDAO();
    }

    public function listar(): array
    {
        return $this->dao->listar();
    }

    public function listarLicoes(): array
    {
        return $this->licaoDao->list();
    }

    public function salvar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $licaoId       = (int)  ($_POST['licao_id']       ?? 0);
        $titulo         = trim($_POST['titulo']          ?? '');
        $enunciado     = trim($_POST['enunciado']       ?? '');
        $tipoInteracao = trim($_POST['tipo_interacao']  ?? '');

        if (!$licaoId || !$titulo || !$enunciado || !$tipoInteracao) {
            $_SESSION['erro_atividade'] = "Preencha todos os campos obrigatórios.";
            header("Location: inserir.php");
            exit;
        }

        $erro = match ($tipoInteracao) {
            'options'     => $this->salvarMultiplaEscolha($licaoId, $titulo, $enunciado),
            'association' => $this->salvarAssociativa($licaoId, $titulo, $enunciado),
            'open'        => $this->salvarAberta($licaoId, $titulo, $enunciado),
            default       => 'Tipo de interação inválido.'
        };

        if (empty($erro)) {
            $_SESSION['sucesso_atividade'] = "Atividade cadastrada com sucesso!";
            header("Location: listar.php");
            exit;
        }

        $_SESSION['erro_atividade'] = $erro;
        header("Location: inserir.php");
        exit;
    }

    public function excluir(): void
    {
        if (isset($_GET['id'])) {
            $this->dao->excluir((int) $_GET['id']);
        }
        header("Location: listar.php");
        exit;
    }

    public function buscarPorId(int $id): ?array
    {
        return $this->dao->findById($id);
    }

    public function atualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id            = (int)  ($_POST['id']            ?? 0);
        $licaoId       = (int)  ($_POST['licao_id']      ?? 0);
        $titulo         = trim($_POST['titulo']        ?? '');
        $enunciado     = trim($_POST['enunciado']     ?? '');
        $tipoInteracao = trim($_POST['tipo_interacao'] ?? '');

        if (!$id || !$licaoId || !$titulo || !$enunciado || !$tipoInteracao) {
            $_SESSION['erro_atividade'] = "Preencha todos os campos obrigatórios.";
            header("Location: editar.php?id=$id");
            exit;
        }

        $erro = match ($tipoInteracao) {
            'options'     => $this->atualizarMultiplaEscolha($id, $licaoId, $titulo, $enunciado),
            'association' => $this->atualizarAssociativa($id, $licaoId, $titulo, $enunciado),
            'open'        => $this->atualizarAberta($id, $licaoId, $titulo, $enunciado),
            default       => 'Tipo inválido.'
        };

        if (empty($erro)) {
            $_SESSION['sucesso_atividade'] = "Atividade atualizada com sucesso!";
            header("Location: listar.php");
            exit;
        }

        $_SESSION['erro_atividade'] = $erro;
        header("Location: editar.php?id=$id");
        exit;
    }

    // ─── Métodos privados de apoio ────────────────────────────────────────────

    private function salvarMultiplaEscolha(int $licaoId, string $titulo, string $enunciado): string
    {
        $opcaoCorreta = (int) ($_POST['opcao_correta'] ?? 0);
        $opcoes = [];

        for ($i = 1; $i <= 4; $i++) {
            $conteudo = trim($_POST["opcao_{$i}_texto"] ?? '');
            if (empty($conteudo)) continue;
            $opcoes[] = [
                'conteudo'     => $conteudo,
                'esta_correta' => ($i === $opcaoCorreta),
            ];
        }

        if (count($opcoes) < 2) {
            return "Informe ao menos 2 opções de resposta.";
        }
        if ($opcaoCorreta < 1 || $opcaoCorreta > 4) {
            return "Selecione a opção correta.";
        }

        return $this->dao->inserirMultiplaEscolha($licaoId, $titulo, $enunciado, $opcoes);
    }

    private function salvarAssociativa(int $licaoId, string $titulo, string $enunciado): string
    {
        $qtd   = (int) ($_POST['qtd_pares'] ?? 4);
        $pares = [];

        for ($i = 1; $i <= $qtd; $i++) {
            $esq = trim($_POST["par_{$i}_esquerdo"] ?? '');
            $dir = trim($_POST["par_{$i}_direito"]  ?? '');
            if ($esq && $dir) {
                $pares[] = ['esquerdo' => $esq, 'direito' => $dir];
            }
        }

        if (count($pares) < 2) {
            return "Informe ao menos 2 pares associativos.";
        }

        return $this->dao->inserirAssociativa($licaoId, $titulo, $enunciado, $pares);
    }

    private function salvarAberta(int $licaoId, string $titulo, string $enunciado): string
    {
        $respostaCorreta = trim($_POST['resposta_correta'] ?? '');

        if (empty($respostaCorreta)) {
            return "Informe a resposta correta.";
        }

        return $this->dao->inserirAberta($licaoId, $titulo, $enunciado, $respostaCorreta);
    }

    private function atualizarMultiplaEscolha(int $id, int $licaoId, string $titulo, string $enunciado): string
    {
        $opcaoCorreta = (int) ($_POST['opcao_correta'] ?? 0);
        $opcoes = [];
        for ($i = 1; $i <= 4; $i++) {
            $conteudo = trim($_POST["opcao_{$i}_texto"] ?? '');
            if (empty($conteudo)) continue;
            $opcoes[] = ['conteudo' => $conteudo, 'esta_correta' => ($i === $opcaoCorreta)];
        }
        if (count($opcoes) < 2) return "Informe ao menos 2 opções.";
        return $this->dao->atualizarMultiplaEscolha($id, $licaoId, $titulo, $enunciado, $opcoes);
    }

    private function atualizarAssociativa(int $id, int $licaoId, string $titulo, string $enunciado): string
    {
        $qtd = (int) ($_POST['qtd_pares'] ?? 4);
        $pares = [];
        for ($i = 1; $i <= $qtd; $i++) {
            $esq = trim($_POST["par_{$i}_esquerdo"] ?? '');
            $dir = trim($_POST["par_{$i}_direito"]  ?? '');
            if ($esq && $dir) $pares[] = ['esquerdo' => $esq, 'direito' => $dir];
        }
        if (count($pares) < 2) return "Informe ao menos 2 pares.";
        return $this->dao->atualizarAssociativa($id, $licaoId, $titulo, $enunciado, $pares);
    }

    private function atualizarAberta(int $id, int $licaoId, string $titulo, string $enunciado): string
    {
        $resposta = trim($_POST['resposta_correta'] ?? '');
        if (empty($resposta)) return "Informe a resposta correta.";
        return $this->dao->atualizarAberta($id, $licaoId, $titulo, $enunciado, $resposta);
    }

   
}
