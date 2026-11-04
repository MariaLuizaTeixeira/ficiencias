<?php

require_once(__DIR__ . "/../model/Aluno.php");
require_once(__DIR__ . "/../dao/AlunoDAO.php"); // o service usa o DAO; sem isso só funcionava por sorte do include de outro arquivo

class AlunoService
{


const VIDAS_MAX         = 5;   // máximo de vidas
const RECARGA_GATILHO   = 2;   // a recarga só começa quando o aluno chega a 2 vidas ou menos
const RECARGA_INTERVALO = 300; // 1 vida a cada 300 segundos (5 minutos)

/** Devolve as vidas que já "venceram" e devolve o total atual. Chame ANTES de mostrar as vidas. */
public function atualizarVidas(int $idUsuario): int
{
    $dao   = new AlunoDAO();
    $aluno = $dao->findByUsuarioId($idUsuario);
    if ($aluno === null) {
        return self::VIDAS_MAX;
    }

    $vidas  = $aluno->getVidas();
    $inicio = $aluno->getRecargaInicio();

    // sem nenhuma vida (inclusive dados antigos): trava até as vidas ficarem completas
    if ($vidas <= 0 && !$aluno->getVidasBloqueadas()) {
        $dao->bloquearVidas($idUsuario);
    }

    // Aluno que já está com 2 vidas ou menos mas ainda sem relógio (ex.: dados antigos): começa a contar agora.
    if ($inicio === null && $vidas <= self::RECARGA_GATILHO) {
        $inicio = time();
        $dao->iniciarRecarga($idUsuario, $inicio);
    }

    if ($inicio !== null) {
        $ganhas = intdiv(time() - $inicio, self::RECARGA_INTERVALO);
        if ($ganhas > 0) {
            $vidas = min(self::VIDAS_MAX, $vidas + $ganhas);
            // encheu: para de contar. Senão, guarda o resto do tempo já decorrido.
            $inicio = ($vidas >= self::VIDAS_MAX) ? null : $inicio + $ganhas * self::RECARGA_INTERVALO;
            $dao->salvarVidas($idUsuario, $vidas, $inicio);
        }
    }

    return $vidas;
}

/**
 * Vidas atuais + quantos segundos faltam para a próxima vida (null = sem recarga rodando).
 * É o que a tela usa para mostrar o temporizador.
 */
public function estadoVidas(int $idUsuario): array
{
    $vidas  = $this->atualizarVidas($idUsuario); // já devolve as vidas vencidas
    $aluno  = (new AlunoDAO())->findByUsuarioId($idUsuario);
    $inicio = $aluno ? $aluno->getRecargaInicio() : null;

    $segundos = null;
    $segundosCheio = null; // tempo até as 5 vidas
    if ($inicio !== null && $vidas < self::VIDAS_MAX) {
        $segundos = max(1, $inicio + self::RECARGA_INTERVALO - time());
        $segundosCheio = $segundos + (self::VIDAS_MAX - $vidas - 1) * self::RECARGA_INTERVALO;
    }
    $bloqueado = $aluno !== null && $aluno->getVidasBloqueadas() && $vidas < self::VIDAS_MAX;
    return ['vidas' => $vidas, 'segundos' => $segundos, 'bloqueado' => $bloqueado, 'segundos_cheio' => $segundosCheio];
}

/** Tira uma vida e devolve quantas sobraram (0 = acabaram). */
public function perderVida(int $idUsuario): int
{
    $dao = new AlunoDAO();

    // usuário antigo do banco pode não ter linha em "alunos": cria com 5 vidas
    if ($dao->findByUsuarioId($idUsuario) === null) {
        $novo = new Aluno();
        $novo->setIdUsuario($idUsuario);
        $novo->setVidas(self::VIDAS_MAX);
        $novo->setSequencia(0);
        $dao->insert($novo);
    }

    $this->atualizarVidas($idUsuario);          // primeiro devolve as vidas que já venceram
    $vidas = $dao->perderVida($idUsuario);
    if ($vidas <= 0) {
        $dao->bloquearVidas($idUsuario); // acabaram: só volta a praticar com as vidas completas
    }

    if ($vidas <= self::RECARGA_GATILHO) {
        $dao->iniciarRecarga($idUsuario, time()); // não faz nada se a recarga já estiver rodando
    }

    return $vidas;
}
    /** Valida o aluno. Devolve a lista de erros (vazia = tudo certo). */
    public function validar(Aluno $aluno): array
    {
        $erros = [];
        try {
            if ($aluno->getVidas() < 0 || $aluno->getVidas() > self::VIDAS_MAX)
                $erros[] = "As vidas precisam estar entre 0 e " . self::VIDAS_MAX . ".";
            if ($aluno->getSequencia() < 0)
                $erros[] = "A sequência não pode ser negativa.";
            if ($aluno->getIdUsuario() <= 0)
                $erros[] = "Aluno sem usuário.";
        } catch (Error $e) { // propriedade ainda não preenchida
            $erros[] = "Dados do aluno incompletos.";
        }
        return $erros;
    }
    
}
