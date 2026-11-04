<?php
$pageTitle  = 'Editar Curso';
$activePage = 'cursos';
require_once(__DIR__ . "/../../../controller/CursoController.php");
$controller = new CursoController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
else if(isset($_GET['id'])) { $curso = $controller->buscarPorId($_GET['id']); if(!$curso) die("Não encontrado."); }
else { header("Location: listar.php"); exit; }
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>📚 Editar Curso</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <input type="hidden" name="id" value="<?= $curso->getId() ?>">
        <div class="form-group">
            <label>Nome do Curso</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($curso->getNome()) ?>" required>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control" rows="4"><?= htmlspecialchars($curso->getDescricao() ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Atualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
