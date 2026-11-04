<?php
$pageTitle  = 'Nova Lição';
$activePage = 'licoes';
require_once(__DIR__ . "/../../../controller/LicaoController.php");
$controller = new LicaoController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
$secoes = $controller->listarSecoes();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>🎯 Nova Lição</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <div class="form-group">
            <label>Seção</label>
            <select name="secao_id" class="form-control" required>
                <option value="">Selecione uma seção...</option>
                <?php foreach($secoes as $s): ?>
                <option value="<?= $s->getId() ?>"><?= htmlspecialchars($s->curso_nome ?? '') ?> → <?= htmlspecialchars($s->getTitulo()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required placeholder="Ex: As Cores em LIBRAS">
        </div>
        <div class="form-group">
            <label>Ordem</label>
            <input type="number" name="ordem" class="form-control" required placeholder="1, 2, 3..." min="1">
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="esta_bloqueada" value="1" checked>
                <span>Lição bloqueada? (Alunos não podem acessar)</span>
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
