<?php
require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Licao.php");

class LicaoDAO
{

    private PDO $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    public function list()
    {
        $sql = "SELECT l.*, s.titulo as secao_titulo, c.nome as curso_nome FROM licoes l JOIN secoes s ON l.secao_id = s.id JOIN cursos c ON s.curso_id = c.id ORDER BY c.nome, s.ordem, l.ordem";

        $stm = $this->conn->prepare($sql);
        $stm->execute();

        return $this->map($stm->fetchAll());
    }

    public function findById(int $id)
    {
        $sql = "SELECT l.*, s.titulo as secao_titulo, c.nome as curso_nome FROM licoes l JOIN secoes s ON l.secao_id = s.id JOIN cursos c ON s.curso_id = c.id WHERE l.id = ?";

        $stm = $this->conn->prepare($sql);
        $stm->execute([$id]);
        $dados = $this->map($stm->fetchAll());

        return !empty($dados) ? $dados[0] : null;
    }

    public function insert(Licao $licao)
    {
        try {
            $sql = "INSERT INTO licoes (secao_id, titulo, ordem, esta_bloqueada) VALUES (:secao_id, :titulo, :ordem, :esta_bloqueada)";

            $stm = $this->conn->prepare($sql);

            $stm->bindValue("secao_id", $licao->getSecaoId());
            $stm->bindValue("titulo", $licao->getTitulo());
            $stm->bindValue("ordem", $licao->getOrdem());
            $stm->bindValue("esta_bloqueada", $licao->getEstaBloqueada() ? 1 : 0, \PDO::PARAM_INT);

            $stm->execute();

            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    public function update(Licao $licao)
    {
        try {
            $sql = "UPDATE licoes SET secao_id = :secao_id, titulo = :titulo, ordem = :ordem, esta_bloqueada = :esta_bloqueada WHERE id = :id";

            $stm = $this->conn->prepare($sql);

            $stm->bindValue("secao_id", $licao->getSecaoId());
            $stm->bindValue("titulo", $licao->getTitulo());
            $stm->bindValue("ordem", $licao->getOrdem());
            $stm->bindValue("esta_bloqueada", $licao->getEstaBloqueada() ? 1 : 0, \PDO::PARAM_INT);
            $stm->bindValue("id", $licao->getId());
            $stm->execute();

            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    public function excluir(int $id)
    {
        try {
            $sql = "DELETE FROM licoes WHERE id = ?";

            $stm = $this->conn->prepare($sql);

            $stm->execute([$id]);

            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (AMB_DEV ? $e->getMessage() : "");
        }
    }

    /**
     * Encontra a próxima lição a ser desbloqueada após a conclusão de uma lição.
     * Primeiro busca na mesma seção (por ordem), depois na primeira lição da próxima seção.
     */
    public function findProxima(Licao $licaoAtual): ?Licao
    {
        // Tenta a próxima lição na mesma seção
        $stm = $this->conn->prepare(
            "SELECT l.* FROM licoes l
             WHERE l.secao_id = ? AND l.ordem > ?
             ORDER BY l.ordem ASC LIMIT 1"
        );
        $stm->execute([$licaoAtual->getSecaoId(), $licaoAtual->getOrdem()]);
        $row = $stm->fetch();

        // Se não houver na mesma seção, busca na próxima seção do mesmo curso
        if (!$row) {
            $stm = $this->conn->prepare(
                "SELECT l.* FROM licoes l
                 JOIN secoes s ON l.secao_id = s.id
                 WHERE s.curso_id = (SELECT curso_id FROM secoes WHERE id = ?)
                   AND s.ordem > (SELECT ordem FROM secoes WHERE id = ?)
                 ORDER BY s.ordem ASC, l.ordem ASC
                 LIMIT 1"
            );
            $stm->execute([$licaoAtual->getSecaoId(), $licaoAtual->getSecaoId()]);
            $row = $stm->fetch();
        }

        return $row ? $this->mapOne($row) : null;
    }

    /**
     * Remove o bloqueio de uma lição pelo seu ID.
     */
    public function desbloquear(int $id): void
    {
        $sql  = "UPDATE licoes SET esta_bloqueada = 0 WHERE id = ?";

        $stm  = $this->conn->prepare($sql);

        $stm->execute([$id]);
    }

    private function map(array $dados)
    {
        $licoes = [];
        foreach ($dados as $d) {
            $licoes[] = $this->mapOne($d);
        }
        return $licoes;
    }

    private function mapOne(array $d): Licao
    {
        $licao = new Licao();
        $licao->setId($d['id']);
        $licao->setSecaoId($d['secao_id']);
        $licao->setTitulo($d["titulo"]);
        $licao->setOrdem($d["ordem"]);
        $licao->setEstaBloqueada((bool) $d["esta_bloqueada"]);
        $licao->secao_titulo = $d['secao_titulo'] ?? null;
        $licao->curso_nome   = $d['curso_nome']   ?? null;
        return $licao;
    }
}
