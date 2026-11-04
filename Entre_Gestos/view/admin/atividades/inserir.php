<?php
$pageTitle  = 'Nova Atividade';
$activePage = 'atividades';
require_once(__DIR__ . "/../../../controller/AtividadeController.php");
$controller = new AtividadeController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $controller->salvar(); }
$licoes = $controller->listarLicoes();
$erro   = $_SESSION['erro_atividade'] ?? '';
unset($_SESSION['erro_atividade']);
require_once(__DIR__ . "/../componentes/admin_header.php");
?>


<div class="page-header">
    <h1>✏️ Nova Atividade</h1>
    <a href="listar.php" class="btn btn-secondary">← Voltar</a>
</div>

<?php if ($erro): ?>
<div class="alerta-erro">⚠ <?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<!-- Indicador de passos -->
<div id="indicadorPassos" class="wizard-steps">
    <div class="wizard-step ativo" data-passo="1">
        <div class="bolinha">1</div> Tipo
    </div>
    <div class="wizard-linha"></div>
    <div class="wizard-step" data-passo="2">
        <div class="bolinha">2</div> Formulário
    </div>
</div>

<!-- ─── Tela 1: Escolha do tipo ─────────────────────────────────────── -->
<div id="tela-tipo" class="form-card wizard-section ativa">
    <p style="text-align:center;color:var(--text-muted);font-weight:700;font-size:15px;margin-bottom:4px;">
        Qual é o tipo de atividade?
    </p>
    <div class="tipos-grid">
        <button type="button" class="tipo-card" data-valor="options">
            <span class="tipo-icon">☑</span>
            Múltipla Escolha
        </button>
        <button type="button" class="tipo-card" data-valor="association">
            <span class="tipo-icon">🔗</span>
            Associativa
        </button>
        <button type="button" class="tipo-card" data-valor="open">
            <span class="tipo-icon">✍</span>
            Escrita
        </button>
    </div>
</div>

<!-- ─── Tela 2: Formulário ──────────────────────────────────────────── -->
<div id="tela-form" class="form-card wizard-section">

    <div id="resumoTela2" class="resumo-tags"></div>

    <form method="POST" id="formAtividade">
        <input type="hidden" name="tipo_interacao" id="input_tipo_interacao">

        <!-- Lição -->
        <div class="form-group">
            <label>Lição</label>
            <select name="licao_id" id="licao_id" class="form-control" required>
                <option value="">Selecione a lição...</option>
                <?php foreach ($licoes as $l): ?>
                <option value="<?= $l->getId() ?>">
                    <?= htmlspecialchars(($l->curso_nome ?? '') . ' › ' . ($l->secao_titulo ?? '') . ' › ' . $l->getTitulo()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Título -->
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required maxlength="150"
                   placeholder="Ex: O que este sinal representa em Libras?">
        </div>

        <!-- Enunciado -->
        <div class="form-group">
            <label>Enunciado</label>
            <textarea id="enunciado" name="enunciado" class="form-control" rows="3"
                      placeholder="Escreva aqui a pergunta ou instrução da atividade..."></textarea>
        </div>

        <!-- ─── Bloco: Múltipla Escolha ──────────────────────────── -->
        <div id="bloco-options" class="bloco-tipo">
            <div style="border-top:2px solid var(--border);padding-top:20px;margin-bottom:16px;">
                <strong style="font-size:15px;">Opções de Resposta</strong>
            </div>
            <div id="opcoesContainer">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="form-group">
                    <label>Opção <?= $i ?></label>
                    <input type="text" name="opcao_<?= $i ?>_texto" class="form-control"
                           placeholder="Texto da opção <?= $i ?>...">
                </div>
                <?php endfor; ?>
            </div>
            <div class="form-group">
                <label>Qual é a Resposta Correta?</label>
                <select name="opcao_correta" id="opcao_correta" class="form-control">
                    <option value="">— Selecione —</option>
                    <option value="1">Opção 1</option>
                    <option value="2">Opção 2</option>
                    <option value="3">Opção 3</option>
                    <option value="4">Opção 4</option>
                </select>
            </div>
        </div>

        <!-- ─── Bloco: Associativa ───────────────────────────────── -->
        <div id="bloco-association" class="bloco-tipo">
            <div style="border-top:2px solid var(--border);padding-top:20px;margin-bottom:8px;">
                <strong style="font-size:15px;">Pares Associativos</strong>
            </div>
            <p style="color:var(--text-muted);font-size:14px;font-weight:600;margin-bottom:16px;">
                Relacione cada item da esquerda com o correspondente da direita.
            </p>
            <div class="pares-grid" id="paresContainer"></div>
            <input type="hidden" name="qtd_pares" id="qtd_pares" value="4">
            <button type="button" id="btnAddPar" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
                + Adicionar par
            </button>
        </div>

        <!-- ─── Bloco: Escrita (Aberta) ─────────────────────────── -->
        <div id="bloco-open" class="bloco-tipo">
            <div style="border-top:2px solid var(--border);padding-top:20px;margin-bottom:16px;">
                <strong style="font-size:15px;">Resposta Esperada</strong>
            </div>
            <div class="form-group">
                <label>Resposta Correta</label>
                <input type="text" name="resposta_correta" class="form-control"
                       placeholder="A resposta que o aluno deve digitar...">
            </div>
        </div>

        <div class="form-actions">
            <button type="button" id="btnVoltar" class="btn btn-secondary">← Voltar</button>
            <button type="submit" class="btn btn-primary">Salvar Atividade ✓</button>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/rupq4ijnqoyqaluhhq0dtziysj5o7bzz79v3t8vl3551haba/tinymce/7/tinymce.min.js"></script>

<script>const BASE_URL = <?= json_encode(BASE_URL) ?>;</script>

<script src="<?= $webBase ?>/public/js/atividades_inserir.js"></script>
<script src="<?= $webBase ?>/public/js/editor_enunciado.js"></script>



<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
