<?php
$pageTitle  = 'Editar Usuário';
$activePage = 'usuarios';
require_once(__DIR__ . "/../../../controller/UsuarioController.php");
require_once(__DIR__ . "/../../../dao/AlunoDAO.php");
require_once(__DIR__ . "/../../../util/validacao_perfil.php");
$controller = new UsuarioController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
else if(isset($_GET['id'])) { $usuario = $controller->buscarPorId($_GET['id']); if(!$usuario) die("Não encontrado."); $aluno = (new AlunoDAO())->findByUsuarioId((int) $usuario->getId()); }
else { header("Location: listar.php"); exit; }
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>👤 Editar Usuário</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
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
            <label>Perfil</label>
            <select name="perfil" class="form-control" required>
                <option value="aluno"          <?= $usuario->getPerfil() === 'aluno'          ? 'selected' : '' ?>>Aluno</option>
                <option value="administrador"  <?= $usuario->getPerfil() === 'administrador'  ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <div class="form-group">
            <label>Apelido <small>(opcional)</small></label>
            <input type="text" name="apelido" class="form-control" maxlength="16" value="<?= htmlspecialchars($aluno ? ($aluno->getApelido() ?? '') : '') ?>" placeholder="Como a pessoa quer ser chamada">
        </div>
        <div class="form-group">
            <label>Telefone</label>
            <input type="tel" name="numero_telefone" class="form-control" maxlength="15" value="<?= htmlspecialchars($aluno ? ($aluno->getNumeroTelefone() ?? '') : '') ?>" placeholder="(45) 99999-9999">
        </div>
        <div class="form-group">
            <label>Nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" min="1900-01-01" max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($aluno ? ($aluno->getDataNascimento() ?? '') : '') ?>">
        </div>
        <div class="form-group">
            <label>Gênero</label>
            <select name="genero" class="form-control">
                <option value="">Não informado</option>
                <?php foreach (perfil_generos() as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>" <?= ($aluno && $aluno->getGenero() === $g) ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="email_verificado" value="1" <?= $usuario->getEmailVerificado() ? 'checked' : '' ?>>
                <span>E-mail verificado</span>
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Atualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
