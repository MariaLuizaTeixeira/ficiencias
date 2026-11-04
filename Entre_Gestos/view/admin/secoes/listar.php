<?php
$pageTitle  = 'Seções';
$activePage = 'secoes';
require_once(__DIR__ . "/../../../controller/SecaoController.php");
$controller = new SecaoController();
$secoes = $controller->listar();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>📂 Seções</h1>
    <a href="inserir.php" class="btn btn-primary">+ Nova Seção</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Curso</th>
                <th>Título</th>
                <th>Ordem</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($secoes as $s): ?>
            <tr>
                <td style="color:var(--text-muted);"><?= $s->getId() ?></td>
                <td><span class="badge badge-blue"><?= htmlspecialchars($s->curso_nome ?? '—') ?></span></td>
                <td><strong><?= htmlspecialchars($s->getTitulo()) ?></strong></td>
                <td><span class="badge badge-orange"><?= $s->getOrdem() ?></span></td>
                <td style="color:var(--text-muted);"><?= htmlspecialchars($s->getDescricao() ?? '—') ?></td>
                <td>
                    <a href="editar.php?id=<?= $s->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="excluir.php?id=<?= $s->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir esta seção e suas lições?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($secoes)): ?>
            <tr><td colspan="6" style="text-align:center; color:var(--text-muted); padding:40px;">Nenhuma seção cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
