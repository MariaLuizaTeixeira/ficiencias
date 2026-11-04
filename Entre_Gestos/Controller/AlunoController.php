<?php

require_once(__DIR__ . "/../dao/AlunoDAO.php");
require_once(__DIR__ . "/../service/AlunoService.php");

class AlunoController {

    private AlunoDAO $alunoDAO;
    private AlunoService $alunoService;

    public function __construct() {
        $this->alunoDAO = new AlunoDAO();
        $this->alunoService = new AlunoService();
    }

    public function listar() {
        return $this->alunoDAO->list();
    }

    public function buscarPorId(int $id) {
        return $this->alunoDAO->findById($id);
    }

    /** Devolve a lista de erros (vazia = salvou). */
    public function inserir(Aluno $aluno): array {
        $erros = $this->alunoService->validar($aluno);

        if (empty($erros)) {
            try {
                $this->alunoDAO->insert($aluno);
            } catch (PDOException $e) {
                $erros[] = "Erro ao salvar o aluno." . (AMB_DEV ? " " . $e->getMessage() : "");
            }
        }

        return $erros;
    }

    /** Devolve a lista de erros (vazia = salvou). */
    public function alterar(Aluno $aluno): array {
        $erros = $this->alunoService->validar($aluno);

        if (empty($erros)) {
            $erroDAO = $this->alunoDAO->update($aluno);
            if ($erroDAO !== "")
                $erros[] = $erroDAO;
        }

        return $erros;
    }

    /** Devolve "" se excluiu ou a mensagem de erro. */
    public function excluir(int $id): string {
        return $this->alunoDAO->excluir($id);
    }

    /**
     * Chamado pela tela da lição quando o aluno erra (via perder_vida.php).
     * Devolve os dados que viram JSON.
     */
    public function perderVida(int $idUsuario): array {
        try {
            $this->alunoService->perderVida($idUsuario);
            return ['ok' => true] + $this->alunoService->estadoVidas($idUsuario);
        } catch (Throwable $e) {
            return ['ok' => false, 'erro' => AMB_DEV ? $e->getMessage() : 'Não foi possível atualizar as vidas.'];
        }
    }

    /** Vidas + segundos até a próxima (JSON do temporizador). */
    public function estadoVidas(int $idUsuario): array {
        try {
            return ['ok' => true] + $this->alunoService->estadoVidas($idUsuario);
        } catch (Throwable $e) {
            return ['ok' => false, 'erro' => AMB_DEV ? $e->getMessage() : 'Não foi possível ler as vidas.'];
        }
    }

}
