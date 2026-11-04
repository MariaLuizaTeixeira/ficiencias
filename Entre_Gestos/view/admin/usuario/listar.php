<?php

//Mostrar erros do PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pageTitle  = 'Usuários';
$activePage = 'usuarios';
require_once(__DIR__ . "/../../../controller/UsuarioController.php");
require_once(__DIR__ . "/../../../dao/AlunoDAO.php");
$controller = new UsuarioController();
$usuarios   = $controller->listar();

// dados extras (apelido, telefone...) indexados pelo id do usuário
$extrasPorUsuario = [];
foreach ((new AlunoDAO())->list() as $al) {
    $extrasPorUsuario[$al->getIdUsuario()] = $al;
}
require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">
    <h1>👤 Usuários</h1>
    <a href="inserir.php" class="btn btn-primary">+ Novo Usuário</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome Completo</th>
                <th>Apelido</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Perfil</th>
                <th>Verificado</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as $u): ?>
            <tr>
                <td style="color:var(--text-muted);"><?= $u->getId() ?></td>
                <td><?= htmlspecialchars($u->getNomeCompleto()) ?></td>
                <?php $ex = $extrasPorUsuario[$u->getId()] ?? null; ?>
                <td><?= $ex && $ex->getApelido() ? htmlspecialchars($ex->getApelido()) : '<span style="color:var(--text-muted);">—</span>' ?></td>
                <td><?= htmlspecialchars($u->getEmail()) ?></td>
                <td><?= $ex && $ex->getNumeroTelefone() ? htmlspecialchars($ex->getNumeroTelefone()) : '<span style="color:var(--text-muted);">—</span>' ?></td>
                <td>
                    <?php if($u->getPerfil() === 'administrador'): ?>
                        <span class="badge badge-blue">Administrador</span>
                    <?php else: ?>
                        <span class="badge badge-purple">Aluno</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($u->getEmailVerificado()): ?>
                        <span class="badge badge-green">✓ Verificado</span>
                    <?php else: ?>
                        <span class="badge badge-red">Pendente</span>
                    <?php endif; ?>
                </td>
                <td style="color:var(--text-muted);"><?= $u->getCriadoEm() ?></td>
                <td>
                    <a href="editar.php?id=<?= $u->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="excluir.php?id=<?= $u->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza? Esta ação não pode ser desfeita.')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($usuarios)): ?>
            <tr><td colspan="9" style="text-align:center; color:var(--text-muted); padding:40px;">Nenhum usuário cadastrado ainda.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
