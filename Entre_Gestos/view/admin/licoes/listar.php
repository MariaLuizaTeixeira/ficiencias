<?php
$pageTitle  = 'Lições';
$activePage = 'licoes';
require_once(__DIR__ . "/../../../controller/LicaoController.php");
$controller = new LicaoController();
$licoes = $controller->listar();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>🎯 Lições</h1>
    <a href="inserir.php" class="btn btn-primary">+ Nova Lição</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Curso / Seção</th>
                <th>Título</th>
                <th>Ordem</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($licoes as $l): ?>
            <tr>
                <td style="color:var(--text-muted);"><?= $l->getId() ?></td>
                <td>
                    <span class="badge badge-blue"><?= htmlspecialchars($l->curso_nome ?? '—') ?></span><br>
                    <small style="color:var(--text-muted); font-weight:700;"><?= htmlspecialchars($l->secao_titulo ?? '') ?></small>
                </td>
                <td><strong><?= htmlspecialchars($l->getTitulo()) ?></strong></td>
                <td><span class="badge badge-orange"><?= $l->getOrdem() ?></span></td>
                <td>
                    <?php if($l->getEstaBloqueada()): ?>
                        <span class="badge badge-red">🔒 Bloqueada</span>
                    <?php else: ?>
                        <span class="badge badge-green">✓ Liberada</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="editar.php?id=<?= $l->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="excluir.php?id=<?= $l->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir esta lição?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($licoes)): ?>
            <tr><td colspan="6" style="text-align:center; color:var(--text-muted); padding:40px;">Nenhuma lição cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
