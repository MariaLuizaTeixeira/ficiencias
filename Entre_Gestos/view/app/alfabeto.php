<?php
require_once(__DIR__ . "/../../controller/AuthController.php");
require_once(__DIR__ . "/../../controller/AlfabetoController.php");
require_once(__DIR__ . "/../../util/youtube.php");

$authCtrl = new AuthController();
$authCtrl->exigirLogin();
$usuarioLogado = $authCtrl->getUsuarioLogado();

$alfabetoCtrl = new AlfabetoController();
$letras = $alfabetoCtrl->listar();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alfabeto em Libras - EntreGestos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="../../public/css/global.css">
   <link rel="stylesheet" href="../../public/css/alfabeto.css">
   <link rel="stylesheet" href="../../public/css/aluno.css">
</head>

<body class="aluno-layout">

    <?php $menuAtivo = 'alfabeto'; require __DIR__ . '/componentes/menu.php'; ?>

    <!-- Conteúdo principal -->
    <main class="al-main">

        <div class="al-page-header">
            <h1>Alfabeto em Libras</h1>
            <a href="trilha.php" class="al-btn al-btn-claro">← Voltar à trilha</a>
        </div>

        <div class="page-intro">
            <h2>Aprenda o alfabeto</h2>
            <p>Clique em uma letra para assistir ao vídeo do sinal.</p>
        </div>

        <div class="alfabeto-grid">
            <?php foreach ($letras as $item): ?>
                <div class="letra-card">
                    <div class="letra-titulo"><?= htmlspecialchars($item->getLetra()) ?></div>

                    <?php if ($item->getVideoId()): ?>
                        <div class="video-card"
                             onclick="abrirVideo('<?= htmlspecialchars(addslashes($item->getLetra())) ?>', '<?= htmlspecialchars(addslashes($item->getVideoId())) ?>', '<?= htmlspecialchars(addslashes($item->getCanalNome())) ?>', '<?= htmlspecialchars(addslashes($item->getCanalUrl())) ?>')">
                            <img class="video-thumb" src="<?= htmlspecialchars(youtube_thumb($item->getVideoId())) ?>"
                                 alt="Vídeo da letra <?= htmlspecialchars($item->getLetra()) ?>" loading="lazy"
                                 onerror="this.style.display='none'">
                            <div class="play-button">▶</div>
                            <div class="video-text">Assistir sinal</div>
                        </div>
                        <a class="canal-link" href="<?= htmlspecialchars($item->getCanalUrl()) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($item->getCanalNome()) ?></a>
                    <?php else: ?>
                        <div class="video-card sem-video">
                            <div class="video-indisponivel">Vídeo em breve</div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if (empty($letras)): ?>
                <p style="grid-column: 1 / -1; text-align:center; color: var(--al-suave);">
                    Nenhuma letra cadastrada ainda.
                </p>
            <?php endif; ?>
        </div>

    </main>

    <!-- Janela do vídeo -->
    <div class="video-modal" id="videoModal" onclick="fecharAoClicarFora(event)">
        <div class="video-modal-conteudo">

            <button class="fechar-video" onclick="fecharVideo()">×</button>

            <div class="video-modal-titulo" id="videoTitulo">Letra em Libras</div>

            <div class="video-frame">
                <iframe
                    id="videoIframe"
                    src=""
                    title="Vídeo em Libras"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>

            <div class="video-creditos">
                Vídeo por: <a id="videoCanal" href="#" target="_blank" rel="noopener noreferrer"></a>
            </div>

        </div>
    </div>

    <!-- Rodapé -->
    <footer class="app-footer">
        <p>EntreGestos — Aprendendo Libras de forma divertida</p>
        <p class="creditos">
            Vídeos cedidos pelo canal <a href="https://www.youtube.com/@incluirtecnologia" target="_blank" rel="noopener noreferrer">Saber Mais</a>
        </p>
    </footer>

    <script src="../../public/JS/alfabeto.js"></script>

</body>

</html>