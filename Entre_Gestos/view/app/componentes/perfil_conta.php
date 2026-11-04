<?php
if (!isset($u) || !isset($admin) || !isset($editando)) {
    $perfilUrl = $perfilUrl ?? 'perfil.php';
    require __DIR__ . '/perfil_acoes.php';
}
$editando = $editando ?? false;
/**
 * PERFIL — cartão "Dados da conta" (com o modo de edição) e,
 * para o aluno, o cartão de excluir a conta.
 * Precisa de perfil_acoes.php incluído antes e de $perfilUrl definida.
 */
?>
<div class="pf-card">
<?php if (!$editando): ?>
    <h2>Dados da conta</h2>
    <div class="pf-linha"><span>Nome</span><b><?= h($u['nome_completo']) ?></b></div>
    <div class="pf-linha"><span>E-mail</span><b><?= h($u['email']) ?></b></div>
    <div class="pf-linha"><span>Senha</span><b>••••••••</b></div>
    <div class="pf-linha"><span>Tipo de conta</span><b><?= $admin ? 'Administrador' : 'Aluno' ?></b></div>
    <div class="pf-linha"><span>Apelido</span><b><?= vazio($a['apelido'] ?? '') ?></b></div>
    <div class="pf-linha"><span>Telefone</span><b><?= vazio($a['numero_telefone'] ?? '') ?></b></div>
    <div class="pf-linha"><span>Nascimento</span><b><?= !empty($a['data_nascimento']) ? h(date('d/m/Y', strtotime($a['data_nascimento']))) : '<i>não informado</i>' ?></b></div>
    <div class="pf-linha"><span>Gênero</span><b><?= vazio($a['genero'] ?? '') ?></b></div>
    <div class="pf-linha"><span>Membro desde</span><b><?= h(date('d/m/Y', strtotime($u['criado_em']))) ?></b></div>
    <a class="pf-btn pf-marrom" href="<?= h($perfilUrl) ?>?editar=1"> Editar perfil</a>
<?php else: ?>
    <h2>Editar meu perfil</h2>
    <form method="POST" action="<?= h($perfilUrl) ?>" autocomplete="off" class="pf-form">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <input type="hidden" name="acao" value="salvar">
        <label>Nome completo</label>
        <input type="text" name="nome_completo" maxlength="64" required value="<?= h($_POST['nome_completo'] ?? $u['nome_completo']) ?>">
        <label>E-mail</label>
        <input type="email" name="email" maxlength="64" required value="<?= h($_POST['email'] ?? $u['email']) ?>">
        <label>Apelido (opcional)</label>
        <input type="text" name="apelido" maxlength="16" value="<?= h($_POST['apelido'] ?? ($a['apelido'] ?? '')) ?>">
        <label>Telefone</label>
        <input type="tel" name="numero_telefone" maxlength="15" placeholder="(45) 99999-9999" value="<?= h($_POST['numero_telefone'] ?? ($a['numero_telefone'] ?? '')) ?>">
        <label>Nascimento</label>
        <input type="date" name="data_nascimento" min="1900-01-01" max="<?= date('Y-m-d') ?>" value="<?= h($_POST['data_nascimento'] ?? ($a['data_nascimento'] ?? '')) ?>">
        <label>Gênero</label>
        <?php $generoAtual = $_POST['genero'] ?? ($a['genero'] ?? ''); ?>
        <select name="genero">
            <option value="">Não informado</option>
            <?php foreach (perfil_generos() as $g): ?>
                <option value="<?= h($g) ?>" <?= $generoAtual === $g ? 'selected' : '' ?>><?= h($g) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Nova senha (deixe em branco para manter)</label>
        <input type="password" name="senha" autocomplete="new-password">
        <button class="pf-btn pf-marrom" type="submit">Atualizar</button>
        <a class="pf-btn pf-cinza" href="<?= h($perfilUrl) ?>">Cancelar</a>
    </form>
<?php endif; ?>
</div>

<?php if (!$admin && !$editando): ?>
<div class="pf-card pf-perigo">
    <h2>Excluir minha conta</h2>
    <p>Apaga seu perfil e todo o progresso. Não dá para desfazer.</p>
    <form method="POST" action="<?= h($perfilUrl) ?>" autocomplete="off" class="pf-form" onsubmit="return confirm('Tem certeza? Isso não pode ser desfeito.')">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <input type="hidden" name="acao" value="excluir">
        <label>Sua senha atual</label>
        <input type="password" name="senha_confirmar" required autocomplete="current-password">
        <button class="pf-btn pf-vermelho" type="submit">🗑️ Excluir minha conta</button>
    </form>
</div>
<?php endif; ?>
