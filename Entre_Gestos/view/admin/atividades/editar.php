<?php
$pageTitle  = 'Editar Atividade';
$activePage = 'atividades';
require_once(__DIR__ . "/../../../controller/AtividadeController.php");

$controller = new AtividadeController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->atualizar();
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header("Location: listar.php");
    exit;
}

$atividade = $controller->buscarPorId($id);
if (!$atividade) {
    header("Location: listar.php");
    exit;
}

$licoes = $controller->listarLicoes();
$erro   = $_SESSION['erro_atividade'] ?? '';
unset($_SESSION['erro_atividade']);

// Converte os tipos do banco para os tipos de interação da tela
$tipoInteracaoMap = [
    'multipla_escolha' => 'options',
    'associacao'       => 'association',
    'aberta'           => 'open'
];
$tipoSelecionado = $tipoInteracaoMap[$atividade['tipo']] ?? '';

require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<style>
/* Modo edição: o tipo já foi escolhido e não pode ser trocado.
   O restante do estilo do wizard está em public/css/formularios.css */
.tipo-card { cursor: not-allowed; color: var(--text-muted); opacity: 0.7; }
.tipo-card:hover { transform: none; border-color: var(--border); box-shadow: 0 4px 0 var(--border); }
.tipo-card.selecionado {
    border-color: var(--green-main);
    background: #f0fff0;
    box-shadow: 0 4px 0 var(--green-shadow);
    color: var(--text-main);
    opacity: 1;
}
.resumo-tag button { display: none; }
</style>

<div class="page-header">
    <h1>✏️ Editar Atividade</h1>
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
        O tipo de atividade não pode ser alterado durante a edição.
    </p>
    <div class="tipos-grid">
        <button type="button" class="tipo-card <?= $tipoSelecionado === 'options' ? 'selecionado' : '' ?>" data-valor="options" disabled>
            <span class="tipo-icon">☑</span>
            Múltipla Escolha
        </button>
        <button type="button" class="tipo-card <?= $tipoSelecionado === 'association' ? 'selecionado' : '' ?>" data-valor="association" disabled>
            <span class="tipo-icon">🔗</span>
            Associativa
        </button>
        <button type="button" class="tipo-card <?= $tipoSelecionado === 'open' ? 'selecionado' : '' ?>" data-valor="open" disabled>
            <span class="tipo-icon">✍</span>
            Escrita
        </button>
    </div>
    
    <div class="form-actions" style="justify-content: center; margin-top: 20px;">
        <button type="button" id="btnAvancar" class="btn btn-primary">Avançar →</button>
    </div>
</div>

<!-- ─── Tela 2: Formulário ──────────────────────────────────────────── -->
<div id="tela-form" class="form-card wizard-section">

    <div id="resumoTela2" class="resumo-tags">
        <span class="resumo-tag" id="tag-resumo">
            <?= $tipoSelecionado === 'options' ? '☑ Múltipla Escolha' : ($tipoSelecionado === 'association' ? '🔗 Associativa' : '✍ Escrita') ?>
            <button type="button" id="btnTrocar">trocar</button>
        </span>
    </div>

    <form method="POST" id="formAtividade">
        <input type="hidden" name="id" value="<?= $atividade['id'] ?>">
        <input type="hidden" name="tipo_interacao" id="input_tipo_interacao" value="<?= htmlspecialchars($tipoSelecionado) ?>">

        <!-- Lição -->
        <div class="form-group">
            <label>Lição</label>
            <select name="licao_id" id="licao_id" class="form-control" required>
                <option value="">Selecione a lição...</option>
                <?php foreach ($licoes as $l): ?>
                <option value="<?= $l->getId() ?>" <?= $l->getId() == $atividade['licao_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars(($l->curso_nome ?? '') . ' › ' . ($l->secao_titulo ?? '') . ' › ' . $l->getTitulo()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Título -->
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required maxlength="150"
                   placeholder="Ex: O que este sinal representa em Libras?"
                   value="<?= htmlspecialchars($atividade['titulo'] ?? '') ?>">
        </div>

        <!-- Enunciado -->
        <div class="form-group">
            <label>Enunciado</label>
            <textarea id="enunciado" name="enunciado" class="form-control" required rows="3"
                      placeholder="Escreva aqui a pergunta ou instrução da atividade..."><?= htmlspecialchars($atividade['enunciado']) ?></textarea>
        </div>

        <!-- ─── Bloco: Múltipla Escolha ──────────────────────────── -->
        <div id="bloco-options" class="bloco-tipo" style="<?= $tipoSelecionado === 'options' ? 'display:block;' : '' ?>">
            <div style="border-top:2px solid var(--border);padding-top:20px;margin-bottom:16px;">
                <strong style="font-size:15px;">Opções de Resposta</strong>
            </div>
            <div id="opcoesContainer">
                <?php 
                if ($tipoSelecionado === 'options') {
                    $opcaoCorretaIdx = '';
                    $opcoes = $atividade['opcoes'] ?? [];
                    for ($i = 1; $i <= 4; $i++) {
                        $idx = $i - 1;
                        $texto = isset($opcoes[$idx]) ? htmlspecialchars($opcoes[$idx]['conteudo']) : '';
                        if (isset($opcoes[$idx]) && $opcoes[$idx]['esta_correta']) {
                            $opcaoCorretaIdx = $i;
                        }
                        ?>
                        <div class="form-group">
                            <label>Opção <?= $i ?></label>
                            <input type="text" name="opcao_<?= $i ?>_texto" class="form-control"
                                   placeholder="Texto da opção <?= $i ?>..." value="<?= $texto ?>">
                        </div>
                        <?php 
                    } 
                } 
                ?>
            </div>
            <div class="form-group">
                <label>Qual é a Resposta Correta?</label>
                <select name="opcao_correta" id="opcao_correta" class="form-control">
                    <option value="">— Selecione —</option>
                    <option value="1" <?= (isset($opcaoCorretaIdx) && $opcaoCorretaIdx == 1) ? 'selected' : '' ?>>Opção 1</option>
                    <option value="2" <?= (isset($opcaoCorretaIdx) && $opcaoCorretaIdx == 2) ? 'selected' : '' ?>>Opção 2</option>
                    <option value="3" <?= (isset($opcaoCorretaIdx) && $opcaoCorretaIdx == 3) ? 'selected' : '' ?>>Opção 3</option>
                    <option value="4" <?= (isset($opcaoCorretaIdx) && $opcaoCorretaIdx == 4) ? 'selected' : '' ?>>Opção 4</option>
                </select>
            </div>
        </div>

        <!-- ─── Bloco: Associativa ───────────────────────────────── -->
        <div id="bloco-association" class="bloco-tipo" style="<?= $tipoSelecionado === 'association' ? 'display:block;' : '' ?>">
            <div style="border-top:2px solid var(--border);padding-top:20px;margin-bottom:8px;">
                <strong style="font-size:15px;">Pares Associativos</strong>
            </div>
            <p style="color:var(--text-muted);font-size:14px;font-weight:600;margin-bottom:16px;">
                Relacione cada item da esquerda com o correspondente da direita.
            </p>
            <div class="pares-grid" id="paresContainer">
                <?php 
                $qtdPares = 4;
                if ($tipoSelecionado === 'association') {
                    $pares = $atividade['pares'] ?? [];
                    $qtdPares = max(4, count($pares));
                    
                    for ($i = 1; $i <= $qtdPares; $i++) {
                        $idx = $i - 1;
                        $esq = isset($pares[$idx]) ? htmlspecialchars($pares[$idx]['esquerdo']) : '';
                        $dir = isset($pares[$idx]) ? htmlspecialchars($pares[$idx]['direito']) : '';
                        ?>
                        <div class="par-linha">
                            <input type="text" name="par_<?= $i ?>_esquerdo" class="form-control"
                                   placeholder="Alternativa <?= $i ?>…" value="<?= $esq ?>">
                            <div class="par-seta">→</div>
                            <input type="text" name="par_<?= $i ?>_direito" class="form-control"
                                   placeholder="Resposta <?= $i ?>…" value="<?= $dir ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <input type="hidden" name="qtd_pares" id="qtd_pares" value="<?= $qtdPares ?>">
            <button type="button" id="btnAddPar" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
                + Adicionar par
            </button>
        </div>

        <!-- ─── Bloco: Escrita (Aberta) ─────────────────────────── -->
        <div id="bloco-open" class="bloco-tipo" style="<?= $tipoSelecionado === 'open' ? 'display:block;' : '' ?>">
            <div style="border-top:2px solid var(--border);padding-top:20px;margin-bottom:16px;">
                <strong style="font-size:15px;">Resposta Esperada</strong>
            </div>
            <div class="form-group">
                <label>Resposta Correta</label>
                <input type="text" name="resposta_correta" class="form-control"
                       placeholder="A resposta que o aluno deve digitar..." 
                       value="<?= $tipoSelecionado === 'open' ? htmlspecialchars($atividade['resposta_correta']) : '' ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="button" id="btnVoltar" class="btn btn-secondary">← Voltar</button>
            <button type="submit" class="btn btn-primary">Salvar Alterações ✓</button>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/rupq4ijnqoyqaluhhq0dtziysj5o7bzz79v3t8vl3551haba/tinymce/7/tinymce.min.js"></script>

<script>const BASE_URL = <?= json_encode(BASE_URL) ?>;</script>

<script src="<?= $webBase ?>/public/js/atividades_editar.js"></script>
<script src="<?= $webBase ?>/public/js/editor_enunciado.js"></script>


<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
