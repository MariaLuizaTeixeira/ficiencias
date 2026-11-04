<?php
require_once(__DIR__ . "/../util/Connection.php");

class AtividadeDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    /**
     * Insere uma questão de múltipla escolha.
     * $opcoes: array de ['conteudo' => string, 'esta_correta' => bool]
     */
    public function inserirMultiplaEscolha(int $licaoId, string $titulo, string $enunciado, array $opcoes): string
    {
        try {
            $this->conn->beginTransaction();

            $stm = $this->conn->prepare(
                "INSERT INTO questoes (titulo, enunciado, tipo, licao_id) VALUES (?, ?, 'multipla_escolha', ?)"
            );
            $stm->execute([$titulo, $enunciado, $licaoId]);
            $questaoId = (int) $this->conn->lastInsertId();

            $stm = $this->conn->prepare(
                "INSERT INTO questoes_multipla_escolha (id, conteudo, tipo_conteudo) VALUES (?, ?, 'texto')"
            );
            $stm->execute([$questaoId, $enunciado]);

            $stm = $this->conn->prepare(
                "INSERT INTO opcoes_questao (questao_id, conteudo, tipo_conteudo, esta_correta) VALUES (?, ?, 'texto', ?)"
            );
            foreach ($opcoes as $op) {
                $stm->execute([$questaoId, $op['conteudo'], $op['esta_correta'] ? 1 : 0]);
            }

            $this->conn->commit();
            return "";
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }

    /**
     * Insere uma questão associativa.
     * $pares: array de ['esquerdo' => string, 'direito' => string]
     */
    public function inserirAssociativa(int $licaoId, string $titulo, string $enunciado, array $pares): string
    {
        try {
            $this->conn->beginTransaction();

            $stm = $this->conn->prepare(
                "INSERT INTO questoes (titulo, enunciado, tipo, licao_id) VALUES (?, ?, 'associacao', ?)"
            );
            $stm->execute([$titulo, $enunciado, $licaoId]);
            $questaoId = (int) $this->conn->lastInsertId();

            $stm = $this->conn->prepare("INSERT INTO questoes_associativas (id) VALUES (?)");
            $stm->execute([$questaoId]);

            $stmItem = $this->conn->prepare(
                "INSERT INTO itens_associativos (conteudo, tipo_conteudo, coluna, questao_id) VALUES (?, 'texto', ?, ?)"
            );
            $stmPar = $this->conn->prepare(
                "INSERT INTO pares_associativos (item_esquerdo_id, item_direito_id) VALUES (?, ?)"
            );

            foreach ($pares as $par) {
                $stmItem->execute([$par['esquerdo'], 'A', $questaoId]);
                $esqId = (int) $this->conn->lastInsertId();

                $stmItem->execute([$par['direito'], 'B', $questaoId]);
                $dirId = (int) $this->conn->lastInsertId();

                $stmPar->execute([$esqId, $dirId]);
            }

            $this->conn->commit();
            return "";
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }

    /**
     * Insere uma questão aberta (escrita).
     */
    public function inserirAberta(int $licaoId, string $titulo, string $enunciado, string $respostaCorreta): string
    {
        try {
            $this->conn->beginTransaction();

            $stm = $this->conn->prepare(
                "INSERT INTO questoes (titulo, enunciado, tipo, licao_id) VALUES (?, ?, 'aberta', ?)"
            );
            $stm->execute([$titulo, $enunciado, $licaoId]);
            $questaoId = (int) $this->conn->lastInsertId();

            $stm = $this->conn->prepare(
                "INSERT INTO questoes_abertas (id, resposta_correta, conteudo, tipo_conteudo) VALUES (?, ?, ?, 'texto')"
            );
            $stm->execute([$questaoId, $respostaCorreta, $enunciado]);

            $this->conn->commit();
            return "";
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }

    /**
     * Lista todas as questões com dados da lição, seção e curso.
     */
    public function listar(): array
    {
        $sql = "
            SELECT q.id, q.titulo, q.enunciado, q.tipo, q.licao_id,
                   l.titulo AS licao_titulo,
                   s.titulo AS secao_titulo,
                   c.nome   AS curso_nome
            FROM questoes q
            JOIN licoes l ON q.licao_id = l.id
            JOIN secoes s ON l.secao_id = s.id
            JOIN cursos c ON s.curso_id = c.id
            ORDER BY c.nome, s.ordem, l.ordem, q.id
        ";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Exclui uma questão pelo ID (ON DELETE CASCADE cuida das sub-tabelas).
     */
    public function excluir(int $id): string
    {
        try {
            $stm = $this->conn->prepare("DELETE FROM questoes WHERE id = ?");
            $stm->execute([$id]);
            return "";
        } catch (\PDOException $e) {
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }

    /**
     * Busca uma questão por ID com todos os seus dados específicos.
     */
    public function findById(int $id): ?array
    {
        $stm = $this->conn->prepare("SELECT * FROM questoes WHERE id = ?");
        $stm->execute([$id]);
        $q = $stm->fetch(\PDO::FETCH_ASSOC);
        if (!$q) return null;

        $result = [
            'id'        => $q['id'],
            'titulo'    => $q['titulo'] ?? '',
            'enunciado' => $q['enunciado'],
            'tipo'      => $q['tipo'],
            'licao_id'  => $q['licao_id'],
        ];

        if ($q['tipo'] === 'multipla_escolha') {
            $stm = $this->conn->prepare(
                "SELECT id, conteudo, esta_correta FROM opcoes_questao WHERE questao_id = ? ORDER BY id"
            );
            $stm->execute([$id]);
            $result['opcoes'] = $stm->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($q['tipo'] === 'associacao') {
            $stm = $this->conn->prepare(
                "SELECT ia.conteudo AS esquerdo, ib.conteudo AS direito
                 FROM pares_associativos p
                 JOIN itens_associativos ia ON ia.id = p.item_esquerdo_id
                 JOIN itens_associativos ib ON ib.id = p.item_direito_id
                 WHERE ia.questao_id = ?"
            );
            $stm->execute([$id]);
            $result['pares'] = $stm->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($q['tipo'] === 'aberta') {
            $stm = $this->conn->prepare("SELECT resposta_correta FROM questoes_abertas WHERE id = ?");
            $stm->execute([$id]);
            $row = $stm->fetch(\PDO::FETCH_ASSOC);
            $result['resposta_correta'] = $row['resposta_correta'] ?? '';
        }

        return $result;
    }

    public function atualizarMultiplaEscolha(int $id, int $licaoId, string $titulo, string $enunciado, array $opcoes): string
    {
        try {
            $this->conn->beginTransaction();

            $stm = $this->conn->prepare("UPDATE questoes SET titulo=?, enunciado=?, licao_id=? WHERE id=?");
            $stm->execute([$titulo, $enunciado, $licaoId, $id]);

            $stm = $this->conn->prepare("UPDATE questoes_multipla_escolha SET conteudo=? WHERE id=?");
            $stm->execute([$enunciado, $id]);

            $stm = $this->conn->prepare("DELETE FROM opcoes_questao WHERE questao_id=?");
            $stm->execute([$id]);

            $stm = $this->conn->prepare(
                "INSERT INTO opcoes_questao (questao_id, conteudo, tipo_conteudo, esta_correta) VALUES (?, ?, 'texto', ?)"
            );
            foreach ($opcoes as $op) {
                $stm->execute([$id, $op['conteudo'], $op['esta_correta'] ? 1 : 0]);
            }

            $this->conn->commit();
            return "";
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }

    public function atualizarAssociativa(int $id, int $licaoId, string $titulo, string $enunciado, array $pares): string
    {
        try {
            $this->conn->beginTransaction();

            $stm = $this->conn->prepare("UPDATE questoes SET titulo=?, enunciado=?, licao_id=? WHERE id=?");
            $stm->execute([$titulo, $enunciado, $licaoId, $id]);

            // Deletar itens (ON DELETE CASCADE remove os pares automaticamente)
            $stm = $this->conn->prepare("DELETE FROM itens_associativos WHERE questao_id=?");
            $stm->execute([$id]);

            $stmItem = $this->conn->prepare(
                "INSERT INTO itens_associativos (conteudo, tipo_conteudo, coluna, questao_id) VALUES (?, 'texto', ?, ?)"
            );
            $stmPar = $this->conn->prepare(
                "INSERT INTO pares_associativos (item_esquerdo_id, item_direito_id) VALUES (?, ?)"
            );

            foreach ($pares as $par) {
                $stmItem->execute([$par['esquerdo'], 'A', $id]);
                $esqId = (int) $this->conn->lastInsertId();

                $stmItem->execute([$par['direito'], 'B', $id]);
                $dirId = (int) $this->conn->lastInsertId();

                $stmPar->execute([$esqId, $dirId]);
            }

            $this->conn->commit();
            return "";
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }

    public function atualizarAberta(int $id, int $licaoId, string $titulo, string $enunciado, string $respostaCorreta): string
    {
        try {
            $this->conn->beginTransaction();

            $stm = $this->conn->prepare("UPDATE questoes SET titulo=?, enunciado=?, licao_id=? WHERE id=?");
            $stm->execute([$titulo, $enunciado, $licaoId, $id]);

            $stm = $this->conn->prepare(
                "UPDATE questoes_abertas SET resposta_correta=?, conteudo=? WHERE id=?"
            );
            $stm->execute([$respostaCorreta, $enunciado, $id]);

            $this->conn->commit();
            return "";
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            return "Erro: " . (defined('AMB_DEV') && AMB_DEV ? $e->getMessage() : "banco de dados.");
        }
    }
}
