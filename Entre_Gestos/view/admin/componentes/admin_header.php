<?php

/**
 * Includes o layout do header da sidebar do admin.
 * $activePage deve ser definida antes de incluir este arquivo.
 * Ex: $activePage = 'usuarios';
 */

require_once __DIR__ . "/../../../util/config.php";
require_once __DIR__ . "/../../../controller/AuthController.php";
require_once __DIR__ . "/../../../util/iniciais.php";

$auth = new AuthController();
$auth->exigirLogin();
$user = $auth->getUsuarioLogado();
if ($user['perfil'] !== 'administrador') {
    die("Acesso negado! Exclusivo para administradores.");
}

$ic = [
    'dashboard'  => '<rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>',
    'usuarios'   => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'cursos'     => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
    'secoes'     => '<path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>',
    'licoes'     => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
    'atividades' => '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>',
    'app'        => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    'sair'       => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
];
$paginas = [
    'dashboard'  => ['label' => 'Painel',     'url' => BASE_URL . '/view/admin/dashboard.php'],
    'usuarios'   => ['label' => 'Usuários',   'url' => BASE_URL . '/view/admin/usuarios/listar.php'],
    'cursos'     => ['label' => 'Cursos',     'url' => BASE_URL . '/view/admin/cursos/listar.php'],
    'secoes'     => ['label' => 'Seções',     'url' => BASE_URL . '/view/admin/secoes/listar.php'],
    'licoes'     => ['label' => 'Lições',     'url' => BASE_URL . '/view/admin/licoes/listar.php'],
    'atividades' => ['label' => 'Atividades', 'url' => BASE_URL . '/view/admin/atividades/listar.php'],
];
$activePage = $activePage ?? 'dashboard';

// Endereço da raiz do projeto, calculado a partir da URL real da página.
// Ex.: /ProjetoEntreGestos/entre_gestos_v3  (funciona com qualquer host/porta)
$webBase = preg_replace('#/view/admin/.*$#', '', str_replace('\\', '/', $_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> - EntreGestos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $webBase ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= $webBase ?>/public/css/admin.css">
    <link rel="stylesheet" href="<?= $webBase ?>/public/css/formularios.css">
    <?php foreach (($extraCss ?? []) as $cssExtra): ?>
    <link rel="stylesheet" href="<?= $webBase ?>/public/css/<?= htmlspecialchars($cssExtra) ?>">
    <?php endforeach; ?>
</head>

<body class="admin-layout">
    <button type="button" class="adm-burger" id="admBurger" aria-label="Abrir menu" aria-expanded="false">☰</button>
    <div class="adm-overlay" id="admOverlay"></div>

    <aside class="sidebar" id="adminSidebar">
        <!-- Mesma "foto" (iniciais) em todas as páginas do admin; clicar leva ao painel (que já inclui o perfil) -->
        <a class="sb-head" href="<?= BASE_URL ?>/view/admin/dashboard.php" title="Meu perfil">
            <span class="sb-avatar"><?= htmlspecialchars(iniciais($user['nome_completo'] ?? '', $user['email'] ?? '')) ?></span>
            <span class="sb-title"><b>Painel Admin</b><small>EntreGestos</small></span>
        </a>

        <div class="sb-logado">
            <small>Logado como:</small>
            <b><?= htmlspecialchars($user['nome_completo'] ?? '') ?></b>
            <small>Administrador</small>
        </div>

        <div class="sb-divider"></div>

        <nav class="sb-nav">
            <?php foreach ($paginas as $key => $pg): ?>
                <a href="<?= $pg['url'] ?>" class="nav-link <?= $activePage === $key ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><?= $ic[$key] ?></svg>
                    <?= $pg['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sb-bottom">
            <a href="<?= BASE_URL ?>/view/app/trilha.php" class="sb-btn sb-app">
                <svg viewBox="0 0 24 24" aria-hidden="true"><?= $ic['app'] ?></svg> Ir para App
            </a>
            <a href="<?= BASE_URL ?>/view/auth/logout.php" class="sb-btn sb-sair">
                <svg viewBox="0 0 24 24" aria-hidden="true"><?= $ic['sair'] ?></svg> Sair
            </a>
        </div>
    </aside>

    <script>
        (function () {
            var botao = document.getElementById('admBurger');
            var fundo = document.getElementById('admOverlay');
            if (!botao || !fundo) return;
            function alternar(aberto) {
                document.body.classList.toggle('adm-aberto', aberto);
                botao.setAttribute('aria-expanded', aberto ? 'true' : 'false');
            }
            botao.addEventListener('click', function () { alternar(!document.body.classList.contains('adm-aberto')); });
            fundo.addEventListener('click', function () { alternar(false); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') alternar(false); });
        })();
    </script>
    <div class="main-content">
