<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Questao.php");
require_once(__DIR__ . "/../model/OpcaoQuestao.php");


class QuestaoDAO
{

    private PDO $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    public function findPrimeiraByLicao(int $licaoId): ?Questao
    {
        $sql = "
            SELECT q.*, m.conteudo AS detalhe_conteudo, m.tipo_conteudo AS detalhe_tipo
            FROM questoes q
            LEFT JOIN questoes_multipla_escolha m ON m.id = q.id
            WHERE q.licao_id = ?
            ORDER BY q.id ASC
            LIMIT 1
        ";

        $stm = $this->conn->prepare($sql);
        $stm->execute([$licaoId]);
        $dados = $stm->fetch();

        if (!$dados) return null;
        return $this->mapOne($dados);
    }

    /**
     * Busca todas as questões de uma lição
     */
    public function findAllByLicao(int $licaoId): array
    {
        $sql = "
            SELECT q.*, m.conteudo AS detalhe_conteudo, m.tipo_conteudo AS detalhe_tipo
            FROM questoes q
            LEFT JOIN questoes_multipla_escolha m ON m.id = q.id
            WHERE q.licao_id = ?
            ORDER BY q.id ASC
        ";

        $stm = $this->conn->prepare($sql);
        $stm->execute([$licaoId]);
        $rows = $stm->fetchAll();
        
        $questoes = [];
        foreach ($rows as $row) {
            $questoes[] = $this->mapOne($row);
        }
        return $questoes;
    }

    /**
     * Busca todas as opções de resposta de uma questão.
     * @return OpcaoQuestao[]
     */
    public function findOpcoesByQuestao(int $questaoId): array
    {
        $sql  = "SELECT * FROM opcoes_questao WHERE questao_id = ?";
        $stm  = $this->conn->prepare($sql);
        $stm->execute([$questaoId]);
        return $this->mapOpcoes($stm->fetchAll());
    }

    /**
     * Busca os pares para questões associativas
     */
    public function findParesByQuestao(int $questaoId): array
    {
        $sql = "
            SELECT ia.conteudo AS esquerdo, ib.conteudo AS direito
            FROM pares_associativos p
            JOIN itens_associativos ia ON ia.id = p.item_esquerdo_id
            JOIN itens_associativos ib ON ib.id = p.item_direito_id
            WHERE ia.questao_id = ?
            ORDER BY RAND()
        ";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$questaoId]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca a resposta para questões abertas
     */
    public function findRespostaAberta(int $questaoId): string
    {
        $sql = "SELECT resposta_correta FROM questoes_abertas WHERE id = ?";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$questaoId]);
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['resposta_correta'] : '';
    }

    private function mapOne(array $d): Questao
    {
        $q = new Questao();
        $q->setId($d['id']);
        $q->setTitulo($d['titulo'] ?? '');
        $q->setEnunciado($d['enunciado']);
        $q->setTipo($d['tipo']);
        $q->setLicaoId($d['licao_id']);
        $q->detalheConteudo = $d['detalhe_conteudo'] ?? null;
        $q->detalheTipo     = $d['detalhe_tipo']     ?? null;
        return $q;
    }

    private function mapOpcoes(array $dados): array
    {
        $opcoes = [];
        foreach ($dados as $d) {
            $op = new OpcaoQuestao();
            $op->setId($d['id']);
            $op->setQuestaoId($d['questao_id']);
            $op->setConteudo($d['conteudo']);
            $op->setTipoConteudo($d['tipo_conteudo']);
            $op->setEstaCorreta((bool) $d['esta_correta']);
            $opcoes[] = $op;
        }
        return $opcoes;
    }
}
