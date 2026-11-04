<?php
$pageTitle  = 'Atividades';
$activePage = 'atividades';

$extraCss = ['tabelas.css'];

require_once(__DIR__ . "/../../../controller/AtividadeController.php");

$controller = new AtividadeController();
$atividades = $controller->listar();

$sucesso = $_SESSION['sucesso_atividade'] ?? '';
unset($_SESSION['sucesso_atividade']);

require_once(__DIR__ . "/../componentes/admin_header.php");
?>

<div class="page-header">

    <h1>Atividades</h1>

    <a href="inserir.php" class="btn btn-primary">
        + Nova Atividade
    </a>

</div>

<?php if ($sucesso): ?>

<div class="mensagem-sucesso">
    <?= htmlspecialchars($sucesso) ?>
</div>

<?php endif; ?>

<div class="table-card">

    <table>

        <thead>

            <tr>
                <th>#</th>
                <th>Curso</th>
                <th>Seção</th>
                <th>Lição</th>
                <th>Título</th>
                <th>Enunciado</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($atividades as $a): ?>

            <tr>

                <td>
                    <?= $a['id'] ?>
                </td>

                <td>

                    <span class="badge badge-blue">
                        <?= htmlspecialchars($a['curso_nome']) ?>
                    </span>

                </td>

                <td>
                    <?= htmlspecialchars($a['secao_titulo']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($a['licao_titulo']) ?>
                </td>

                <td>

                    <?php

                    $tituloLista = trim($a['titulo'] ?? '');

                    if ($tituloLista === '') {

                        $tituloLista = trim(
                            html_entity_decode(
                                strip_tags($a['enunciado'])
                            )
                        );

                        if ($tituloLista === '') {
                            $tituloLista = '(sem título)';
                        }
                    }

                    ?>

                    <strong>
                        <?= htmlspecialchars(
                            mb_strimwidth(
                                $tituloLista,
                                0,
                                70,
                                '…'
                            )
                        ) ?>
                    </strong>

                </td>

                <td>

                    <?php

                    $enunciado = trim($a['enunciado'] ?? '');

                    preg_match(
                        '/<img[^>]+src=["\']([^"\']+)["\']/i',
                        $enunciado,
                        $imagem
                    );

                    preg_match(
                        '/<iframe[^>]+src=["\']([^"\']+)["\']/i',
                        $enunciado,
                        $iframe
                    );

                    preg_match(
                        '/<video[^>]+src=["\']([^"\']+)["\']/i',
                        $enunciado,
                        $video
                    );

                    $youtubeId = '';

                    if (!empty($iframe[1])) {

                        $urlVideo = $iframe[1];

                        if (preg_match(
                            '/(?:youtube\.com\/embed\/|youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
                            $urlVideo,
                            $youtube
                        )) {

                            $youtubeId = $youtube[1];

                        }

                    }

                    $textoEnunciado = trim(
                        html_entity_decode(
                            strip_tags($enunciado)
                        )
                    );

                    ?>

                    <?php if (!empty($imagem[1])): ?>

                        <div class="enunciado-miniatura">

                            <img
                                src="<?= htmlspecialchars($imagem[1]) ?>"
                                alt="Imagem da atividade"
                            >

                        </div>

                    <?php elseif (!empty($youtubeId)): ?>

                        <div class="enunciado-miniatura youtube-miniatura">

                            <img
                                src="https://img.youtube.com/vi/<?= htmlspecialchars($youtubeId) ?>/hqdefault.jpg"
                                alt="Vídeo da atividade"
                            >

                            <span class="youtube-play">
                                ▶
                            </span>

                        </div>

                    <?php elseif (!empty($video[1])): ?>

                        <div class="enunciado-miniatura enunciado-video">

                            <video
                                src="<?= htmlspecialchars($video[1]) ?>"
                                muted
                                preload="metadata"
                            ></video>

                        </div>

                    <?php elseif ($textoEnunciado !== ''): ?>

                        <?= htmlspecialchars(
                            mb_strimwidth(
                                $textoEnunciado,
                                0,
                                100,
                                '…'
                            )
                        ) ?>

                    <?php else: ?>

                        Sem enunciado

                    <?php endif; ?>

                </td>

                <td>

                    <?php

                    $badges = [

                        'multipla_escolha' => [
                            'badge-green',
                            'Múltipla Escolha'
                        ],

                        'associacao' => [
                            'badge-purple',
                            'Associativa'
                        ],

                        'aberta' => [
                            'badge-orange',
                            'Escrita'
                        ]

                    ];

                    [$classe, $nomeTipo] =
                        $badges[$a['tipo']]
                        ?? ['badge-blue', $a['tipo']];

                    ?>

                    <span class="badge <?= $classe ?>">
                        <?= $nomeTipo ?>
                    </span>

                </td>

                <td class="acoes-atividade">

                    <a
                        href="editar.php?id=<?= $a['id'] ?>"
                        class="btn btn-warning btn-sm"
                    >
                        Editar
                    </a>

                    <a
                        href="excluir.php?id=<?= $a['id'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Excluir esta atividade e todas as suas opções?')"
                    >
                        Excluir
                    </a>

                </td>

            </tr>

            <?php endforeach; ?>

            <?php if (empty($atividades)): ?>

            <tr>

                <td colspan="8">
                    Nenhuma atividade cadastrada.
                </td>

            </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once(__DIR__ . "/../componentes/admin_footer.php"); ?>
