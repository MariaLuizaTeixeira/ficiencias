<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Aluno.php");

class AlunoDAO
{

    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getConnection();
    }

    public function list()
    {
        $sql = "SELECT * FROM alunos";
        $stm = $this->connection->prepare($sql);
        $stm->execute();
        $dados = $stm->fetchAll();
        return $this->map($dados);
    }

    public function insert(Aluno $aluno): Aluno
    {
        $sql = "INSERT INTO alunos (id_usuario, vidas, sequencia, apelido, numero_telefone, data_nascimento, genero)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stm = $this->connection->prepare($sql);
        $stm->execute([
            $aluno->getIdUsuario(),
            $aluno->getVidas(),
            $aluno->getSequencia(),
            $aluno->getApelido(),
            $aluno->getNumeroTelefone(),
            $aluno->getDataNascimento(),
            $aluno->getGenero(),
        ]);
        $aluno->setId($this->connection->lastInsertId());

        return $aluno;
    }

    public function findById(int $id): ?Aluno
    {
        $stm = $this->connection->prepare("SELECT * FROM alunos WHERE id = ?");
        $stm->execute([$id]);
        $dados = $stm->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->mapOne($dados) : null;
    }

    /** Atualiza o aluno. Devolve "" se deu certo ou a mensagem de erro. */
    public function update(Aluno $aluno): string
    {
        try {
            $sql = "UPDATE alunos
                    SET vidas = ?, sequencia = ?, apelido = ?, numero_telefone = ?, data_nascimento = ?, genero = ?
                    WHERE id = ?";
            $this->connection->prepare($sql)->execute([
                $aluno->getVidas(),
                $aluno->getSequencia(),
                $aluno->getApelido(),
                $aluno->getNumeroTelefone(),
                $aluno->getDataNascimento(),
                $aluno->getGenero(),
                $aluno->getId(),
            ]);
            return "";
        } catch (PDOException $e) {
            return "Erro ao atualizar o aluno." . (AMB_DEV ? " " . $e->getMessage() : "");
        }
    }

    /** Exclui o aluno pelo id da tabela alunos. Devolve "" se deu certo ou a mensagem de erro. */
    public function excluir(int $id): string
    {
        try {
            $this->connection->prepare("DELETE FROM alunos WHERE id = ?")->execute([$id]);
            return "";
        } catch (PDOException $e) {
            return "Erro ao excluir o aluno." . (AMB_DEV ? " " . $e->getMessage() : "");
        }
    }

    public function findByUsuarioId(int $idUsuario): ?Aluno
    {
        $stm = $this->connection->prepare("SELECT * FROM alunos WHERE id_usuario = ?");
        $stm->execute([$idUsuario]);
        $dados = $stm->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->mapOne($dados) : null;
    }

    /**
     * Salva apelido, telefone, nascimento e gênero de um usuário (aluno OU administrador).
     * Se ele ainda não tiver linha na tabela alunos, cria uma (5 vidas, sequência 0).
     */
    public function salvarExtras(int $idUsuario, array $extras): void
    {
        $valores = [
            $extras['apelido'] ?? null,
            $extras['numero_telefone'] ?? null,
            $extras['data_nascimento'] ?? null,
            $extras['genero'] ?? null,
        ];

        $stm = $this->connection->prepare("SELECT id FROM alunos WHERE id_usuario = ?");
        $stm->execute([$idUsuario]);

        if ($stm->fetch()) {
            $this->connection->prepare(
                "UPDATE alunos SET apelido = ?, numero_telefone = ?, data_nascimento = ?, genero = ? WHERE id_usuario = ?"
            )->execute(array_merge($valores, [$idUsuario]));
        } else {
            $this->connection->prepare(
                "INSERT INTO alunos (id_usuario, vidas, sequencia, apelido, numero_telefone, data_nascimento, genero)
                 VALUES (?, 5, 0, ?, ?, ?, ?)"
            )->execute(array_merge([$idUsuario], $valores));
        }
    }

    private function mapOne(array $alunoData): Aluno
    {
        $aluno = new Aluno();
        $aluno->setId($alunoData['id']);
        $aluno->setIdUsuario($alunoData['id_usuario']);
        $aluno->setVidas($alunoData['vidas']);
        $aluno->setSequencia($alunoData['sequencia']);
        $aluno->setApelido($alunoData['apelido'] ?? null);
        $aluno->setNumeroTelefone($alunoData['numero_telefone'] ?? null);
        $aluno->setDataNascimento($alunoData['data_nascimento'] ?? null);
        $aluno->setGenero($alunoData['genero'] ?? null);
        $aluno->setVidasBloqueadas(!empty($alunoData['vidas_bloqueadas']));
        $aluno->setRecargaInicio(isset($alunoData['recarga_inicio']) ? (int) $alunoData['recarga_inicio'] : null);

        return $aluno;
    }

    private function map(array $dados): array
    {
        $alunos = array();

        foreach ($dados as $d) {
            array_push($alunos, $this->mapOne($d));
        }

        return $alunos;
    }
  /** Tira 1 vida do aluno (nunca abaixo de 0) e devolve quantas sobraram. */
public function perderVida(int $idUsuario): int
{
    $stm = $this->connection->prepare("UPDATE alunos SET vidas = vidas - 1 WHERE id_usuario = ? AND vidas > 0");
    $stm->execute([$idUsuario]);

    $stm = $this->connection->prepare("SELECT vidas FROM alunos WHERE id_usuario = ?");
    $stm->execute([$idUsuario]);
    return (int) $stm->fetchColumn();
}

/** Grava as vidas e o início da recarga (null = nenhuma recarga em andamento). */
public function salvarVidas(int $idUsuario, int $vidas, ?int $recargaInicio): void
{
    // vidas completas (5) também destravam o aluno
    $stm = $this->connection->prepare("UPDATE alunos SET vidas = ?, recarga_inicio = ?, vidas_bloqueadas = CASE WHEN CAST(? AS INTEGER) >= 5 THEN 0 ELSE vidas_bloqueadas END WHERE id_usuario = ?");
    $stm->execute([$vidas, $recargaInicio, $vidas, $idUsuario]);
}

/** As vidas acabaram: trava até as vidas ficarem completas. */
public function bloquearVidas(int $idUsuario): void
{
    $stm = $this->connection->prepare("UPDATE alunos SET vidas_bloqueadas = 1 WHERE id_usuario = ?");
    $stm->execute([$idUsuario]);
}

/** Começa a contar a recarga, mas só se ela ainda não estiver rodando. */
public function iniciarRecarga(int $idUsuario, int $agora): void
{
    $stm = $this->connection->prepare("UPDATE alunos SET recarga_inicio = ? WHERE id_usuario = ? AND recarga_inicio IS NULL");
    $stm->execute([$agora, $idUsuario]);
}

}
