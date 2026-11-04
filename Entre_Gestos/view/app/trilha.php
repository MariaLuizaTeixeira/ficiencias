<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../../util/config.php");


require_once(__DIR__ . "/../../controller/AuthController.php");
require_once(__DIR__ . "/../../controller/LicaoController.php");
require_once(__DIR__ . "/../../controller/SecaoController.php");
require_once(__DIR__ . "/../../dao/ProgressoDAO.php");
require_once(__DIR__ . "/../../dao/AlunoDAO.php");
require_once(__DIR__ . "/../../service/AlunoService.php");
require_once(__DIR__ . "/../../util/iniciais.php");

$authCtrl = new AuthController();
$authCtrl->exigirLogin();
$alunoId = $authCtrl->getUsuarioLogadoId();
$usuarioLogado = $authCtrl->getUsuarioLogado();

$licaoCtrl = new LicaoController();
$secaoCtrl = new SecaoController();
$progressoDao = new ProgressoDAO();

$licoesConcluidas = $progressoDao->getLicoesConcluidas($alunoId);

// Busca todas as lições e seções
$todasLicoes = $licaoCtrl->listar();
$secoes = $secaoCtrl->listar();

// Agrupa as lições por seção
$trilha = [];
foreach ($secoes as $secao) {
    $trilha[$secao->getId()] = [
        'secao' => $secao,
        'licoes' => []
    ];
}

// Vidas e apelido do aluno (mostrados no cabeçalho)
$alunoLogado = (new AlunoDAO())->findByUsuarioId((int) $alunoId);
$estadoVidas   = (new AlunoService())->estadoVidas((int) $alunoId); // já soma as vidas que a recarga devolveu
$vidas         = $estadoVidas['vidas'];
$vidasSegundos = $estadoVidas['segundos']; // segundos até a próxima vida (null = vidas cheias)
$headerApelido = $alunoLogado ? ($alunoLogado->getApelido() ?? '') : '';

$isLocked = false;
foreach ($todasLicoes as $licao) {
    $licaoId = $licao->getId();
    $completada = in_array($licaoId, $licoesConcluidas);

    if (!$isLocked) {
        if (!$completada) {
            $status = 'lesson-current';
            $isLocked = true; // A partir daqui, as próximas estarão bloqueadas
        } else {
            $status = 'lesson-unlocked';
        }
    } else {
        $status = 'lesson-locked';
    }

    $licao->status_calculado = $status;

    if (isset($trilha[$licao->getSecaoId()])) {
        $trilha[$licao->getSecaoId()]['licoes'][] = $licao;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprender LIBRAS - EntreGestos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="../../public/css/global.css">
   <link rel="stylesheet" href="../../public/css/trilha.css">
   <link rel="stylesheet" href="../../public/css/aluno.css">
</head>

<body class="aluno-layout sem-menu">

    <?php require __DIR__ . '/componentes/header.php'; ?>

    <!-- Conteúdo principal -->
    <main class="al-main">
    <div class="trilha-centro">
        <div class="path-container">

            <?php
            foreach ($trilha as $item):
                $secao = $item['secao'];
                $licoes = $item['licoes'];
            ?>

                <div class="section-header">
                    <h2>Unidade <?= $secao->getOrdem() ?></h2>
                    <p><?= htmlspecialchars($secao->getTitulo()) ?></p>
                </div>

                <div class="timeline">
                    <div class="timeline-line"></div>

                    <?php foreach ($licoes as $index => $licao):
                        $btnClass = $licao->status_calculado; // lesson-locked (bloqueada), lesson-unlocked (concluída), lesson-current (atual)
                        $locked = ($btnClass === 'lesson-locked');

                        // Alterna entre esquerda e direita
                        $sideClass = ($index % 2 == 0) ? 'item-left' : 'item-right';
                    ?>

                        <div class="timeline-item <?= $sideClass ?> <?= $btnClass ?>">
                            <div class="timeline-dot"></div>

                            <button class="timeline-card"
                                <?= $locked && $btnClass !== 'lesson-current' ? 'disabled' : '' ?>
                                onclick="if(!this.disabled) window.location.href='licao.php?id=<?= $licao->getId() ?>'">

                                <div class="card-icon">
                                    <?php if ($btnClass === 'lesson-locked'): ?>
                                        🔒
                                    <?php elseif ($btnClass === 'lesson-current'): ?>
                                        ▶
                                    <?php else: ?>
                                        ★
                                    <?php endif; ?>
                                </div>

                                <div class="card-content">
                                    <div class="card-title">Lição <?= $licao->getOrdem() ?></div>
                                    <div class="card-subtitle"><?= htmlspecialchars($licao->getTitulo()) ?></div>
                                </div>

                                <div class="card-stars">
                                    <?php if ($btnClass === 'lesson-unlocked'): ?>
                                        ★★★
                                    <?php elseif ($btnClass === 'lesson-current'): ?>
                                        ☆☆☆
                                    <?php else: ?>

                                    <?php endif; ?>
                                </div>
                            </button>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php endforeach; ?>

            <?php if (empty($trilha)): ?>
                <div style="text-align: center; color: var(--al-suave); margin-top: 50px;">
                    <h2>Nenhuma lição cadastrada ainda!</h2>
                    <p>Vá no Admin e crie Cursos, Seções e Lições.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
    </main>

</body>

</html>