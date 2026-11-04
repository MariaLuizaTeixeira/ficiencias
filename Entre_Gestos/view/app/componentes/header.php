<?php
/**
 * CABEÇALHO do aluno (usado na trilha, no lugar da barra lateral).
 * Mostra: marca | vidas | bolinha com as iniciais e o nome (clica e abre uma caixinha com Perfil, Alfabeto e Sair).
 *
 * Antes de incluir, defina (opcional):
 *   $vidas        = 5;   // número de vidas do aluno
 *   $headerApelido = '';  // apelido; se vazio, usa o nome completo
 *   $vidasSegundos = null; // segundos até a próxima vida (null = sem temporizador)
 * Precisa de $_SESSION['usuario_logado']. O CSS está em public/css/aluno.css.
 */
require_once(__DIR__ . "/../../../util/config.php");
require_once(__DIR__ . "/../../../util/iniciais.php");

$hUsuario = $_SESSION['usuario_logado'] ?? [];
$hNomeCompleto = (string) ($hUsuario['nome_completo'] ?? '');
$hNome  = trim((string) ($headerApelido ?? '')) !== '' ? trim($headerApelido) : $hNomeCompleto;
$hVidas = (int) ($vidas ?? 5);
$hSegundos = isset($vidasSegundos) ? (int) $vidasSegundos : null;
?>
<header class="al-topbar">
    <div class="tb-esq">
        <span class="tb-marca">EntreGestos</span>
    </div>

    <div class="tb-dir">
        <div class="al-vidas" title="Suas vidas" aria-label="<?= $hVidas ?> vidas">❤️ <span id="vidasNum"><?= $hVidas ?></span> <small id="vidasTempo" class="al-vidas-tempo" hidden></small></div>

        <!-- Bolinha com as iniciais e o nome: clica e abre a caixinha Perfil / Alfabeto -->
        <div class="tb-menu" id="tbMenu">
            <button type="button" class="tb-perfil" id="tbBotao" aria-haspopup="true" aria-expanded="false" title="Menu">
                <span class="tb-avatar" aria-hidden="true"><?= htmlspecialchars(iniciais($hNomeCompleto, $hUsuario['email'] ?? '')) ?></span>
                <span class="tb-nome"><?= htmlspecialchars($hNome) ?></span>
            </button>

            <div class="tb-caixa" id="tbCaixa" role="menu">
                <a href="<?= BASE_URL ?>/view/app/perfil.php" role="menuitem">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Perfil
                </a>
                <a href="<?= BASE_URL ?>/view/app/alfabeto.php" role="menuitem">
                    <!-- sinal do alfabeto: "Aa" -->
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 19 7.5 5 13 19"/><path d="M4 14h7"/><circle cx="18" cy="15" r="3.2"/><path d="M21.2 12v7"/></svg>
                    Alfabeto
                </a>
                <a href="<?= BASE_URL ?>/view/auth/logout.php" role="menuitem" class="tb-sair">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Sair
                </a>
            </div>
        </div>
    </div>
</header>

<div class="aviso-vida" id="avisoVida" role="status" aria-live="polite" hidden></div>

<script>
    // abre/fecha a caixinha ao clicar na bolinha; fecha ao clicar fora ou com Esc
    (function () {
        var menu = document.getElementById('tbMenu');
        var botao = document.getElementById('tbBotao');
        if (!menu || !botao) return;
        function alternar(aberto) {
            menu.classList.toggle('aberto', aberto);
            botao.setAttribute('aria-expanded', aberto ? 'true' : 'false');
        }
        botao.addEventListener('click', function (e) { e.stopPropagation(); alternar(!menu.classList.contains('aberto')); });
        document.addEventListener('click', function (e) { if (!menu.contains(e.target)) alternar(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') alternar(false); });
    })();
</script>

<script>
    // Temporizador da próxima vida (o tempo certo vem do servidor)
    (function () {
        var num = document.getElementById('vidasNum');
        var tempo = document.getElementById('vidasTempo');
        var aviso = document.getElementById('avisoVida');
        var restante = <?= json_encode($hSegundos) ?>;
        var relogio = null, timerAviso = null;

        function fmt(s) { return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0'); }

        function avisar(texto) {
            aviso.innerText = texto;
            aviso.hidden = false;
            aviso.classList.remove('mostra');
            void aviso.offsetWidth;
            aviso.classList.add('mostra');
            clearTimeout(timerAviso);
            timerAviso = setTimeout(function () { aviso.hidden = true; }, 4000);
        }

        function iniciar(segundos) {
            clearInterval(relogio);
            restante = (typeof segundos === 'number') ? segundos : null;
            if (restante === null) { tempo.hidden = true; return; }
            tempo.hidden = false;
            tempo.innerText = '⏱ ' + fmt(restante);
            relogio = setInterval(function () {
                restante--;
                if (restante <= 0) { clearInterval(relogio); tempo.innerText = '⏱ 0:00'; recuperou(); return; }
                tempo.innerText = '⏱ ' + fmt(restante);
            }, 1000);
        }

        // acabou o tempo: o servidor diz quantas vidas ela tem agora
        function recuperou() {
            fetch('vidas.php').then(function (r) { return r.json(); }).then(function (d) {
                if (!d.ok) return;
                var antes = parseInt(num.innerText, 10);
                num.innerText = d.vidas;
                if (d.vidas > antes) avisar(d.vidas >= 5 ? 'Vidas cheias! ❤️' : 'Você ganhou uma vida! ❤️');
                iniciar(d.segundos);
            }).catch(function () {});
        }

        iniciar(restante);
    })();
</script>
