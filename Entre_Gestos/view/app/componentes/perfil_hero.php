<?php
/**
 * PERFIL — cartão com avatar (iniciais), nome, e-mail e tipo de conta.
 * Precisa de perfil_acoes.php incluído antes ($u e $admin).
 */
?>
<div class="pf-card pf-hero">
    <div class="pf-foto" aria-hidden="true"><?= h(iniciais($u['nome_completo'], $u['email'])) ?></div>
    <div>
        <h1><?= h($u['nome_completo']) ?></h1>
        <p><?= h($u['email']) ?></p>
        <span class="pf-selo <?= $admin ? 'admin' : '' ?>"><?= $admin ? 'Administrador' : 'Aluno' ?></span>
    </div>
</div>
