<?php
$pageTitle  = 'Editar Seção';
$activePage = 'secoes';
require_once(__DIR__ . "/../../../controller/SecaoController.php");
$controller = new SecaoController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
else if(isset($_GET['id'])) { $secao = $controller->buscarPorId($_GET['id']); if(!$secao) die("Não encontrado."); }
else { header("Location: listar.php"); exit; }
$cursos = $controller->listarCursos();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>📂 Editar Seção</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <input type="hidden" name="id" value="<?= $secao->getId() ?>">
        <div class="form-group">
            <label>Curso</label>
            <select name="curso_id" class="form-control" required>
                <?php foreach($cursos as $c): ?>
                <option value="<?= $c->getId() ?>" <?= $secao->getCursoId() == $c->getId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c->getNome()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($secao->getTitulo()) ?>" required>
        </div>
        <div class="form-group">
            <label>Ordem</label>
            <input type="number" name="ordem" class="form-control" value="<?= $secao->getOrdem() ?>" required min="1">
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($secao->getDescricao() ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Atualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
