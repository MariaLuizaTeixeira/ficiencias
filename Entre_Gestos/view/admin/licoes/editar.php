<?php
$pageTitle  = 'Editar Lição';
$activePage = 'licoes';
require_once(__DIR__ . "/../../../controller/LicaoController.php");
$controller = new LicaoController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
else if(isset($_GET['id'])) { $licao = $controller->buscarPorId($_GET['id']); if(!$licao) die("Não encontrado."); }
else { header("Location: listar.php"); exit; }
$secoes = $controller->listarSecoes();
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1> Editar Lição</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <input type="hidden" name="id" value="<?= $licao->getId() ?>">
        <div class="form-group">
            <label>Seção</label>
            <select name="secao_id" class="form-control" required>
                <?php foreach($secoes as $s): ?>
                <option value="<?= $s->getId() ?>" <?= $licao->getSecaoId() == $s->getId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s->curso_nome ?? '') ?> → <?= htmlspecialchars($s->getTitulo()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($licao->getTitulo()) ?>" required>
        </div>
        <div class="form-group">
            <label>Ordem</label>
            <input type="number" name="ordem" class="form-control" value="<?= $licao->getOrdem() ?>" required min="1">
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="esta_bloqueada" value="1" <?= $licao->getEstaBloqueada() ? 'checked' : '' ?>>
                <span>Lição bloqueada? (Alunos não podem acessar)</span>
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Atualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
