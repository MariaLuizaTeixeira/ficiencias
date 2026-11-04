<?php
$pageTitle  = 'Editar Usuário';
$activePage = 'usuarios';
require_once(__DIR__ . "/../../../controller/UsuarioController.php");
$controller = new UsuarioController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
else if(isset($_GET['id'])) { $usuario = $controller->buscarPorId($_GET['id']); if(!$usuario) die("Não encontrado."); }
else { header("Location: listar_aluno.php"); exit; }
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>👤 Editar Usuário</h1>
    <a href="listar_aluno.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="nome_completo" class="form-control" value="<?= htmlspecialchars($usuario->getNomeCompleto()) ?>" required>
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
        </div>
       
         <div class="form-group">
            <label> Nova Senha</label>
            <input type="password" name="senha" class="form-control" required placeholder="Mínimo 8 caracteres">
        </div>
       
       
        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Atualizar</button>
            <a href="trilha.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
