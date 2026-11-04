<?php
/**
 * PERFIL do aluno (painel com o mesmo visual do admin).
 * Aluno: vê, atualiza e exclui o PRÓPRIO perfil.
 * Admin: o perfil dele fica junto do painel (admin/dashboard.php), então é redirecionado para lá.
 * A lógica e os cartões ficam em componentes/perfil_*.php (compartilhados com o painel do admin).
 */
$perfilUrl = 'perfil.php';
require_once(__DIR__ . "/componentes/perfil_acoes.php");

// admin usa o painel único: dashboard + perfil na mesma página
if ($admin) {
    header("Location: " . BASE_URL . "/view/admin/dashboard.php" . (isset($_GET['editar']) ? '?editar=1' : ''));
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meu Perfil - EntreGestos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/aluno.css">
    <link rel="stylesheet" href="../../public/css/perfil.css">
</head>
<body class="aluno-layout">

<?php $menuAtivo = 'perfil'; require __DIR__ . '/componentes/menu.php'; ?>

<main class="al-main">
    <div class="al-page-header">
        <h1>Meu Perfil</h1>
        <a class="al-btn al-btn-claro" href="trilha.php">← Voltar à trilha</a>
    </div>

    <div class="pf-centro">
        <?php if ($msg):  ?><div class="pf-aviso pf-ok"><?= h($msg) ?></div><?php endif; ?>
        <?php if ($erro): ?><div class="pf-aviso pf-nok"><?= h($erro) ?></div><?php endif; ?>

        <?php require __DIR__ . '/componentes/perfil_hero.php'; ?>

        <div class="pf-card">
            <h2>Meu progresso</h2>
            <div class="pf-stats">
                <div>❤️ <?= (int) ($a['vidas'] ?? 5) ?><small>Vidas</small></div>
                <div>🔥 <?= (int) ($a['sequencia'] ?? 0) ?><small>Sequência</small></div>
                <div>⭐ <?= $feitas ?><small>Lições feitas</small></div>
            </div>
            <div class="pf-barra"><span style="width:<?= $pct ?>%"></span></div>
            <p class="pf-texto-barra"><?= $feitas ?> de <?= $total ?> lições (<?= $pct ?>%)</p>
        </div>

        <?php require __DIR__ . '/componentes/perfil_conta.php'; ?>
    </div>
</main>

</body>
</html>
