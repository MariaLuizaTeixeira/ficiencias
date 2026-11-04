<?php
$pageTitle  = 'Novo Usuário';
$activePage = 'usuarios';
require_once(__DIR__ . "/../../../controller/UsuarioController.php");
require_once(__DIR__ . "/../../../util/validacao_perfil.php");
$controller = new UsuarioController();
if($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>👤 Novo Usuário</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="form-card">
    <form method="POST">
        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="nome_completo" class="form-control" required placeholder="Ex: Maria da Silva">
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" class="form-control" required placeholder="email@exemplo.com">
        </div>
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" class="form-control" required placeholder="Mínimo 8 caracteres">
        </div>
        <div class="form-group">
            <label>Tipo de conta</label>
            <select name="perfil" class="form-control" required>
                <option value="aluno">Aluno</option>
                <option value="administrador">Administrador</option>
            </select>
        </div>
        <div class="form-group">
            <label>Apelido <small>(opcional)</small></label>
            <input type="text" name="apelido" class="form-control" maxlength="16" placeholder="Como a pessoa quer ser chamada">
        </div>
        <div class="form-group">
            <label>Telefone</label>
            <input type="tel" name="numero_telefone" class="form-control" maxlength="15" required placeholder="(45) 99999-9999">
        </div>
        <div class="form-group">
            <label>Nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" required min="1900-01-01" max="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group">
            <label>Gênero</label>
            <select name="genero" class="form-control" required>
                <option value="" disabled selected>Selecione</option>
                <?php foreach (perfil_generos() as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>"><?= htmlspecialchars($g) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="email_verificado" value="1">
                <span>E-mail já verificado</span>
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
