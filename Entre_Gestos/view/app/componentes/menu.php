<?php
/**
 * MENU LATERAL do aluno — mesmo visual da barra marrom do painel admin.
 * Itens: Trilha, Alfabeto, Conquistas e Perfil.
 * Se o usuário for administrador, aparece também o botão "Painel Admin".
 *
 * Uso (logo depois do <body class="aluno-layout">):
 *   $menuAtivo = 'trilha';            // qual item fica destacado
 *   require __DIR__ . '/componentes/menu.php';
 * O CSS está em public/css/aluno.css. Precisa de $_SESSION['usuario_logado'].
 */
require_once(__DIR__ . "/../../../util/config.php");
require_once(__DIR__ . "/../../../util/iniciais.php");

$menuUsuario = $_SESSION['usuario_logado'] ?? [];
$menuEhAdmin = (($menuUsuario['perfil'] ?? '') === 'administrador');
$menuAtivo   = $menuAtivo ?? 'trilha';

// ícones (traços SVG, os mesmos do estilo do admin)
$menuIcones = [
    'trilha'     => '<polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/>',
    'alfabeto'   => '<polyline points="4 7 4 4 20 4 20 7"/><line x1="9" x2="15" y1="20" y2="20"/><line x1="12" x2="12" y1="4" y2="20"/>',
    'conquistas' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>',
    'perfil'     => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'admin'      => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    'sair'       => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
];

$menuItens = [
    'trilha'     => ['label' => 'Trilha',     'url' => BASE_URL . '/view/app/trilha.php'],
    'alfabeto'   => ['label' => 'Alfabeto',   'url' => BASE_URL . '/view/app/alfabeto.php'],
    'conquistas' => ['label' => 'Conquistas', 'url' => '#'],
    'perfil'     => ['label' => 'Perfil',     'url' => BASE_URL . '/view/app/perfil.php'],
];
?>
<button type="button" class="al-burger" id="alBurger" aria-label="Abrir menu" aria-expanded="false">☰</button>
<div class="al-overlay" id="alOverlay"></div>

<aside class="sidebar" id="alSidebar">
    <!-- Avatar com as iniciais; clicar leva ao perfil -->
    <a class="sb-head" href="<?= BASE_URL ?>/view/app/perfil.php" title="Meu perfil">
        <span class="sb-avatar"><?= htmlspecialchars(iniciais($menuUsuario['nome_completo'] ?? '', $menuUsuario['email'] ?? '')) ?></span>
        <span class="sb-title"><b>EntreGestos</b><small><?= $menuEhAdmin ? 'Modo aluno' : 'Área do aluno' ?></small></span>
    </a>

    <div class="sb-logado">
        <small>Logado como:</small>
        <b><?= htmlspecialchars($menuUsuario['nome_completo'] ?? '') ?></b>
        <small><?= $menuEhAdmin ? 'Administrador' : 'Aluno' ?></small>
    </div>

    <div class="sb-divider"></div>

    <nav class="sb-nav">
        <?php foreach ($menuItens as $chave => $it): ?>
            <a href="<?= htmlspecialchars($it['url']) ?>" class="nav-link <?= $menuAtivo === $chave ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><?= $menuIcones[$chave] ?></svg>
                <?= $it['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sb-bottom">
        <?php if ($menuEhAdmin): ?>
            <a href="<?= BASE_URL ?>/view/admin/dashboard.php" class="sb-btn sb-app">
                <svg viewBox="0 0 24 24" aria-hidden="true"><?= $menuIcones['admin'] ?></svg> Painel Admin
            </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/view/auth/logout.php" class="sb-btn sb-sair">
            <svg viewBox="0 0 24 24" aria-hidden="true"><?= $menuIcones['sair'] ?></svg> Sair
        </a>
    </div>
</aside>

<script>
    // abre/fecha a gaveta do menu no celular
    (function () {
        var botao = document.getElementById('alBurger');
        var fundo = document.getElementById('alOverlay');
        if (!botao || !fundo) return;
        function alternar(aberto) {
            document.body.classList.toggle('al-aberto', aberto);
            botao.setAttribute('aria-expanded', aberto ? 'true' : 'false');
        }
        botao.addEventListener('click', function () { alternar(!document.body.classList.contains('al-aberto')); });
        fundo.addEventListener('click', function () { alternar(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') alternar(false); });
    })();
</script>
