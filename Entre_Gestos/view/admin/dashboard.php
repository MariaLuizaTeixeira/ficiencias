<?php
/**
 * PAINEL DO ADMIN — dashboard + perfil do administrador na mesma página.
 * Em cima: números do conteúdo. Embaixo: dados da conta (com edição),
 * atalhos rápidos e o cartão com avatar.
 */
$pageTitle  = 'Painel';
$activePage = 'dashboard';
$extraCss   = ['dashboard.css', 'perfil.css'];

require_once(__DIR__ . "/../../controller/CursoController.php");
require_once(__DIR__ . "/../../controller/SecaoController.php");
require_once(__DIR__ . "/../../controller/LicaoController.php");
require_once(__DIR__ . "/../../controller/AtividadeController.php");

// lógica do perfil (salvar dados); o formulário volta para esta mesma página
$perfilUrl = 'dashboard.php';
require_once(__DIR__ . "/../app/componentes/perfil_acoes.php");

// só administrador usa este painel
if (!$admin) {
    header("Location: " . BASE_URL . "/view/app/perfil.php");
    exit;
}

$totalCursos     = count((new CursoController())->listar());
$totalSecoes     = count((new SecaoController())->listar());
$totalLicoes     = count((new LicaoController())->listar());
$totalAtividades = count((new AtividadeController())->listar());

require_once(__DIR__ . "/componentes/admin_header.php");
?>

<div class="page-header">
    <h1>Painel do Administrador</h1>
</div>

<?php if ($msg):  ?><div class="pf-aviso pf-ok"><?= h($msg) ?></div><?php endif; ?>
<?php if ($erro): ?><div class="pf-aviso pf-nok"><?= h($erro) ?></div><?php endif; ?>

<!-- Números do conteúdo -->
<div class="dash-grid">
    <a href="<?= BASE_URL ?>/view/admin/cursos/listar.php" class="dash-card cursos">
        <div class="card-icon">📚</div>
        <div class="card-info">
            <h3><?= $totalCursos ?></h3>
            <p>Cursos ativos</p>
        </div>
    </a>
    <a href="<?= BASE_URL ?>/view/admin/secoes/listar.php" class="dash-card secoes">
        <div class="card-icon">📂</div>
        <div class="card-info">
            <h3><?= $totalSecoes ?></h3>
            <p>Seções criadas</p>
        </div>
    </a>
    <a href="<?= BASE_URL ?>/view/admin/licoes/listar.php" class="dash-card licoes">
        <div class="card-icon">🎯</div>
        <div class="card-info">
            <h3><?= $totalLicoes ?></h3>
            <p>Lições disponíveis</p>
        </div>
    </a>
    <a href="<?= BASE_URL ?>/view/admin/atividades/listar.php" class="dash-card atividades">
        <div class="card-icon">✏️</div>
        <div class="card-info">
            <h3><?= $totalAtividades ?></h3>
            <p>Atividades cadastradas</p>
        </div>
    </a>
</div>

<!-- Perfil do admin: avatar + atalhos à esquerda, dados da conta (editável) à direita -->
<div class="pf-duas">
    <div class="pf-coluna">
        <?php require __DIR__ . '/../app/componentes/perfil_hero.php'; ?>

        <div class="pf-card">
            <h2>Ações rápidas</h2>
            <div class="pf-atalhos">
                <a href="usuarios/listar.php">👤 Alterar perfis</a>
                <a href="usuarios/inserir.php">➕ Novo usuário</a>
                <a href="atividades/inserir.php">✏️ Cadastrar atividade</a>
            </div>
        </div>
    </div>

    <div class="pf-coluna">
        <?php require __DIR__ . '/../app/componentes/perfil_conta.php'; ?>
    </div>
</div>

<?php require_once(__DIR__ . "/componentes/admin_footer.php"); ?>
