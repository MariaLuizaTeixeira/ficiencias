<?php
$pageTitle  = 'Cursos';
$activePage = 'cursos';
require_once(__DIR__ . "/../../../controller/CursoController.php");
$controller = new CursoController();
$cursos = $controller->listar();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>📚 Cursos</h1>
    <a href="inserir.php" class="btn btn-primary">+ Novo Curso</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cursos as $c): ?>
            <tr>
                <td style="color:var(--text-muted);"><?= $c->getId() ?></td>
                <td><strong><?= htmlspecialchars($c->getNome()) ?></strong></td>
                <td style="color:var(--text-muted);"><?= htmlspecialchars($c->getDescricao() ?? '—') ?></td>
                <td>
                    <a href="editar.php?id=<?= $c->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="excluir.php?id=<?= $c->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir curso e tudo associado a ele?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($cursos)): ?>
            <tr><td colspan="4" style="text-align:center; color:var(--text-muted); padding:40px;">Nenhum curso cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
