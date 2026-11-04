<?php

require_once(__DIR__ . "/../util/Connection.php");

class ProgressoDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    /**
     * Retorna um array com os IDs das lições concluídas por um aluno.
     */
    public function getLicoesConcluidas(int $alunoId): array
    {
        $sql = "SELECT licao_id FROM progresso_licoes WHERE aluno_id = ? AND completada = 1";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$alunoId]);
        
        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Marca uma lição como concluída para um aluno.
     */
    public function concluirLicao(int $alunoId, int $licaoId): void
    {
        // Verifica se já existe registro
        $sqlCheck = "SELECT id FROM progresso_licoes WHERE aluno_id = ? AND licao_id = ?";
        $stmCheck = $this->conn->prepare($sqlCheck);
        $stmCheck->execute([$alunoId, $licaoId]);
        
        $existe = $stmCheck->fetch();

        if ($existe) {
            $sql = "UPDATE progresso_licoes SET completada = 1, completado_em = NOW() WHERE aluno_id = ? AND licao_id = ?";
            $stm = $this->conn->prepare($sql);
            $stm->execute([$alunoId, $licaoId]);
        } else {
            $sql = "INSERT INTO progresso_licoes (aluno_id, licao_id, completada, completado_em) VALUES (?, ?, 1, NOW())";
            $stm = $this->conn->prepare($sql);
            $stm->execute([$alunoId, $licaoId]);
        }
    }
}
