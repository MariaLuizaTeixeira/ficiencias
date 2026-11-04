<?php
$pageTitle  = 'Novo Curso';
$activePage = 'cursos';
require_once(__DIR__ . "/../../../controller/CursoController.php");
$controller = new CursoController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>📚 Novo Curso</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <div class="form-group">
            <label>Nome do Curso</label>
            <input type="text" name="nome" class="form-control" required placeholder="Ex: LIBRAS Intermediário">
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva o objetivo do curso..."></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
