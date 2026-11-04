<?php
require_once(__DIR__ . "/../../controller/AuthController.php");
require_once(__DIR__ . "/../../service/AlunoService.php");
require_once(__DIR__ . "/../../controller/LicaoController.php");
$auth = new AuthController();
$auth->exigirLogin();



$controller = new LicaoController();

$licaoId = $_GET['id'] ?? null;
if (!$licaoId) {
    header("Location: trilha.php");
    exit;
}

$licao   = $controller->buscarPorId((int) $licaoId);
if (!$licao) {
    die("Lição não encontrada.");
}

$resultado = $controller->buscarTodasQuestoes((int) $licaoId);

$q_idx = isset($_GET['q_idx']) ? (int)$_GET['q_idx'] : 0;
$totalQuestoes = count($resultado);

if ($q_idx < $totalQuestoes) {
    $questaoData = $resultado[$q_idx];
    $questao = $questaoData['questao'];
    $tipo = $questao->getTipo();
    $opcoes = $questaoData['opcoes'] ?? [];
    $pares = $questaoData['pares'] ?? [];
    $respostaCorreta = $questaoData['resposta_correta'] ?? '';
    $progresso = ($totalQuestoes > 0) ? ($q_idx / $totalQuestoes) * 100 : 0;
} else {
    $questao = null;
    $opcoes = [];
    $progresso = 100;
}

if($questao) {
    $enunciado = str_replace(
    '../../../public/uploads/videos/',
    '../../public/uploads/videos/',
    $questao->getEnunciado());

    $enunciado = str_replace(
    '../../../public/uploads/images/',
    '../../public/uploads/images/',
    $enunciado);

    $enunciado = preg_replace(
    '/(<video\b[^>]*?)\s+style=(["\']).*?\2/i',
    '$1', $enunciado);

    $enunciado = preg_replace(
    '/\s+(?:width|height)\s*=\s*(["\']).*?\1/i',
    '',$enunciado);
}


// Vidas reais do aluno logado (já soma as que a recarga devolveu; quem nunca errou tem 5)
$estadoVidas = (new AlunoService())->estadoVidas((int) $auth->getUsuarioLogadoId());
$vidas = $estadoVidas['vidas'];

// Sem vidas: não deixa abrir a lição até as vidas ficarem completas
if ($estadoVidas['bloqueado']) {
    $faltam = (int) ($estadoVidas['segundos_cheio'] ?? 0);
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sem vidas</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/licao.css">
</head>
<body>
    <div class="sem-vidas">
        <img src="../assets/imagens/motivacao.png" alt="">
        <h1>Suas vidas acabaram</h1>
        <p>Volte quando as vidas estiverem completas e pratique!</p>
        <div class="sem-vidas-tempo">❤️ <?= (int) $vidas ?>/<?= AlunoService::VIDAS_MAX ?> · ⏱ <span id="faltam">--:--</span></div>
        <a href="trilha.php" class="check-btn active sem-vidas-btn">VOLTAR À TRILHA</a>
    </div>
    <script>
        let faltam = <?= $faltam ?>;
        const el = document.getElementById('faltam');
        function mostrar() {
            const h = Math.floor(faltam / 3600), m = Math.floor((faltam % 3600) / 60), s = faltam % 60;
            el.innerText = (h ? h + ':' + String(m).padStart(2, '0') : m) + ':' + String(s).padStart(2, '0');
        }
        mostrar();
        setInterval(() => {
            faltam--;
            if (faltam <= 0) { location.href = 'trilha.php'; return; } // vidas completas: volta para a trilha
            mostrar();
        }, 1000);
    </script>
</body>
</html>
<?php
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lição - <?= htmlspecialchars($licao->getTitulo()) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/licao.css">
</head>
<body>
    <div class="header">
        <a href="trilha.php" class="close-btn">✖</a>
        <div class="progress-bar"><div class="progress-fill" style="width: <?= $progresso ?>%;"></div></div>
        <div style="color: #ff4b4b; font-weight: 800; font-size: 20px;">❤️ <span id="vidas"><?= (int) $vidas ?></span></div>
    </div>

    <div class="question-container">
        <?php if ($questao): ?>
            <div class="title"><?= trim((string) $questao->getTitulo()) !== '' ? htmlspecialchars($questao->getTitulo()) : 'Traduza esta frase' ?></div>
            <div class="enunciado"><?= ($questao->getEnunciado()) ?></div>

            <?php if ($tipo === 'multipla_escolha'): ?>
                <div class="options-grid" id="options">
                    <?php foreach ($opcoes as $op): ?>
                        <button class="option-btn"
                                data-correct="<?= $op->getEstaCorreta() ? '1' : '0' ?>"
                                onclick="selectOption(this)">
                            <?= htmlspecialchars($op->getConteudo()) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($tipo === 'aberta'): ?>
                <input type="text" id="resposta_aberta" class="input-aberta" placeholder="Digite sua resposta..." oninput="checkInput()">
                <input type="hidden" id="correct_answer" value="<?= htmlspecialchars($respostaCorreta) ?>">
            <?php elseif ($tipo === 'associacao'): 
                $direitos = array_column($pares, 'direito');
                shuffle($direitos);
            ?>
                <div id="assoc-container">
                    <?php foreach ($pares as $idx => $par): ?>
                        <div class="assoc-row" data-correct="<?= htmlspecialchars($par['direito']) ?>">
                            <div class="assoc-left"><?= htmlspecialchars($par['esquerdo']) ?></div>
                            <select class="assoc-right" onchange="checkAssoc()">
                                <option value="">-- Escolha --</option>
                                <?php foreach($direitos as $dir): ?>
                                    <option value="<?= htmlspecialchars($dir) ?>"><?= htmlspecialchars($dir) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="title">Lição concluída! 🎉</div>
        <?php endif; ?>
    </div>

    <div class="footer" id="footer">
        <div class="footer-content">
            <div class="feedback-lado">
                <div id="feedback" style="font-size: 22px; font-weight: 900; display: none;"></div>
            </div>
            <?php if ($questao): ?>
                <button class="check-btn" id="checkBtn" onclick="checkAnswer()">VERIFICAR</button>
            <?php else: ?>
                <button class="check-btn active" onclick="window.location.href='concluir_licao.php?id=<?= $licao->getId() ?>'">PRÓXIMA ATIVIDADE</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Capivara ao lado da pergunta: chora / anima quando erra, comemora quando acerta -->
    <div class="capivara-balao" id="capivaraBalao" hidden></div>
    <img class="capivara-lado" id="capivaraLado" alt="" hidden>

    <script>
        // Valores vindos do PHP para o licao.js
        const LICAO_CONFIG = {
            tipoQuestao: "<?= isset($tipo) ? $tipo : '' ?>",
            proximaUrl: <?= (isset($q_idx) && isset($totalQuestoes) && $q_idx + 1 < $totalQuestoes)
                ? json_encode('licao.php?id=' . $licao->getId() . '&q_idx=' . ($q_idx + 1)) : 'null' ?>,
            concluirUrl: <?= json_encode('concluir_licao.php?id=' . $licao->getId()) ?>,
            trilhaUrl: "trilha.php",
            urlPerderVida: "perder_vida.php",
            // regras de vida (vêm do AlunoService, o JS não repete número nenhum)
            vidasGatilho: <?= AlunoService::RECARGA_GATILHO ?>,
            minutosRecarga: <?= (int) (AlunoService::RECARGA_INTERVALO / 60) ?>,
            // imagens das telas de derrota / ânimo / vitória
            imagens: {
                derrota:   "../assets/imagens/derrota.png",
                motivacao: "../assets/imagens/motivacao.png",
                vitoria:   "../assets/imagens/vitoria.png"
            },
            progressoPercentual: <?= ($totalQuestoes > 0) ? (($q_idx + 1) / $totalQuestoes) * 100 : 100 ?>
        };
    </script>
    <script src="../../public/js/licao.js"></script>
</body>
</html>
