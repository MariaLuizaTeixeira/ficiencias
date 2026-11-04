<?php
$pageTitle  = 'Nova Seção';
$activePage = 'secoes';
require_once(__DIR__ . "/../../../controller/SecaoController.php");
$controller = new SecaoController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
$cursos = $controller->listarCursos();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>📂 Nova Seção</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <div class="form-group">
            <label>Curso</label>
            <select name="curso_id" class="form-control" required>
                <option value="">Selecione um curso...</option>
                <?php foreach($cursos as $c): ?>
                <option value="<?= $c->getId() ?>"><?= htmlspecialchars($c->getNome()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required placeholder="Ex: Saudações do Dia a Dia">
        </div>
        <div class="form-group">
            <label>Ordem</label>
            <input type="number" name="ordem" class="form-control" required placeholder="1, 2, 3..." min="1">
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control" rows="3" placeholder="Descreva o objetivo da seção..."></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
