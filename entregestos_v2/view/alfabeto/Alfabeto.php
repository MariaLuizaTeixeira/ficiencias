<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alfabeto em Libras - EntreGestos</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg-color: #fdfaf6;
            --text-main: #3c3c3c;
            --text-muted: #afafaf;
            --border: #e5e5e5;
            --green-main: #58cc02;
            --blue-main: #1cb0f6;
            --blue-shadow: #1899d6;
            --card-brown: #8c7355;
            --card-white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* =========================
           CABEÇALHO
        ========================= */

        .app-header {
            width: 100%;
            background: var(--card-white);
            border-bottom: 2px solid var(--border);
            padding: 18px 30px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            position: sticky;
            top: 0;
            z-index: 50;
        }

        .app-header .logo {
            font-size: 22px;
            font-weight: 900;
            color: var(--green-main);
        }

        .app-header h1 {
            font-size: 20px;
            font-weight: 800;
        }

        /* =========================
           CONTEÚDO
        ========================= */

        .main-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }

        .page-intro {
            text-align: center;
            margin-bottom: 35px;
        }

        .page-intro h2 {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .page-intro p {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* =========================
           GRID
        ========================= */

        .alfabeto-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        /* =========================
           CARD
        ========================= */

        .letra-card {
            background: var(--card-white);
            border: 2px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 4px 0 var(--border);

            padding: 16px;
            text-align: center;

            transition:
                transform 0.1s,
                border-color 0.15s,
                box-shadow 0.15s;
        }

        .letra-card:hover {
            border-color: var(--blue-main);
            box-shadow: 0 4px 0 var(--blue-shadow);
            transform: translateY(-2px);
        }

        .letra-titulo {
            font-size: 34px;
            font-weight: 900;
            color: var(--card-brown);
            margin-bottom: 12px;
        }

        /* =========================
           ÁREA DO VÍDEO NO CARD
        ========================= */

        .video-card {
            width: 100%;
            height: 160px;
            background: #0d1c1c;
            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .video-card:hover {
            filter: brightness(0.9);
        }

        /* BOTÃO PLAY */

        .play-button {
            width: 55px;
            height: 55px;

            border-radius: 50%;

            background: var(--blue-main);
            box-shadow: 0 4px 0 var(--blue-shadow);

            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;

            transition: transform 0.15s;
        }

        .video-card:hover .play-button {
            transform: scale(1.1);
        }

        .video-text {
            position: absolute;
            bottom: 12px;
            left: 0;
            right: 0;

            color: white;
            font-size: 13px;
            font-weight: 800;
        }

        /* =========================
           JANELA DO VÍDEO
        ========================= */

        .video-modal {
            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, 0.80);

            display: none;

            align-items: center;
            justify-content: center;

            padding: 20px;

            z-index: 9999;
        }

        .video-modal.aberto {
            display: flex;
        }

        /* CAIXA GRANDE */

        .video-modal-conteudo {
            width: 100%;
            max-width: 850px;

            background: var(--card-white);

            border-radius: 20px;

            padding: 20px;

            position: relative;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* TÍTULO */

        .video-modal-titulo {
            text-align: center;

            font-size: 26px;
            font-weight: 900;

            color: var(--card-brown);

            margin-bottom: 15px;
        }

        /* VÍDEO GRANDE */

        .video-frame {
            width: 100%;

            aspect-ratio: 16 / 9;

            border-radius: 12px;

            overflow: hidden;

            background: #000;
        }

        .video-frame iframe {
            width: 100%;
            height: 100%;

            border: none;
        }

        /* CRÉDITOS DO VÍDEO */

        .video-creditos {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
        }

        .video-creditos a {
            color: var(--blue-main);
            text-decoration: none;
            font-weight: 800;
        }

        .video-creditos a:hover {
            text-decoration: underline;
        }

        /* BOTÃO FECHAR */

        .fechar-video {
            position: absolute;

            top: -15px;
            right: -15px;

            width: 40px;
            height: 40px;

            border-radius: 50%;

            border: 2px solid var(--border);

            background: #ffffff;

            color: var(--text-main);

            font-size: 22px;
            font-weight: 900;

            cursor: pointer;

            display: flex;
            align-items: center;
            justify-content: center;

            box-shadow: 0 3px 0 var(--border);

            z-index: 10;
        }

        .fechar-video:hover {
            background: #f5f5f5;
        }

        /* =========================
           RODAPÉ
        ========================= */

        .app-footer {
            width: 100%;
            background: var(--card-white);
            border-top: 2px solid var(--border);

            margin-top: 50px;
            padding: 24px 20px;

            text-align: center;
        }

        .app-footer p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .app-footer p.creditos {
            margin-top: 6px;
        }

        .app-footer a {
            color: var(--blue-main);
            font-weight: 800;
            text-decoration: none;
        }

        .app-footer a:hover {
            text-decoration: underline;
        }

        /* =========================
           TABLET
        ========================= */

        @media (max-width: 900px) {

            .alfabeto-grid {
                grid-template-columns: repeat(3, 1fr);
            }

        }

        /* =========================
           CELULAR
        ========================= */

        @media (max-width: 600px) {

            .alfabeto-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }

            .main-content {
                padding: 30px 14px 50px;
            }

            .letra-card {
                padding: 12px;
            }

            .letra-titulo {
                font-size: 28px;
            }

            .video-card {
                height: 130px;
            }

            .play-button {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }

            .app-header {
                padding: 15px;
            }

            .app-header h1 {
                font-size: 17px;
            }

            .video-modal-conteudo {
                padding: 12px;
            }

        }
    </style>

</head>


<body>


    <!-- =========================
     CABEÇALHO
========================= -->

    <header class="app-header">

        <div class="logo">
            EntreGestos
        </div>

        <h1>
            Alfabeto em Libras
        </h1>

    </header>


    <!-- =========================
     CONTEÚDO
========================= -->

    <main class="main-content">

        <section class="page-intro">

            <h2>
                Alfabeto em Libras
            </h2>

            <p>
                Clique em uma letra para assistir ao vídeo do sinal.
            </p>

        </section>


        <section class="alfabeto-grid">





            <!-- A -->
            <div class="letra-card">
                <div class="letra-titulo">A</div>
                <div
                    class="video-card"
                    onclick="abrirVideo('A', 'JSBlCLlnOjg')">
                    <div class="play-button">
                        ▶
                    </div>
                    <div class="video-text">
                        Assistir sinal
                    </div>
                </div>
            </div>

            <!-- B -->
            <div class="letra-card">
                <div class="letra-titulo">B</div>
                <div
                    class="video-card"
                    onclick="abrirVideo('B', 'gQUFF0w_4WA')">
                    <div class="play-button">▶</div>
                    <div class="video-text">Assistir sinal</div>
                </div>
            </div>


            <!-- C -->

            <div class="letra-card">

                <div class="letra-titulo">C</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('C', 'nf9gBzoUI68')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>

            <!-- ç -->

            <div class="letra-card">

                <div class="letra-titulo">Ç</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('Ç', '6669YqT9uQA')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>



            <!-- D -->

            <div class="letra-card">

                <div class="letra-titulo">D</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('D', 'W6G5_iJHO4s')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- E -->

            <div class="letra-card">

                <div class="letra-titulo">E</div>

                <div class="video-card" onclick="abrirVideo('E', 'yS-PGhIp9wc')">

                    <div class="play-button">▶</div>

                    <div class="video-text"> Assistir sinal</div>

                </div>

            </div>


            <!-- F -->

            <div class="letra-card">

                <div class="letra-titulo">F</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('F', 'ngG5Eih07GM')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- G -->

            <div class="letra-card">

                <div class="letra-titulo">G</div>

                <div class="video-card" onclick="abrirVideo('G', '142vjBvDT2Y')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- H -->

            <div class="letra-card">

                <div class="letra-titulo">H</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('H', 'GmCu1WWKzXk')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- I -->

            <div class="letra-card">

                <div class="letra-titulo">I</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('I', 'HsF0pWCZKWo')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- J -->

            <div class="letra-card">

                <div class="letra-titulo">J</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('J', 'I2vSibIAsIE')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- K -->

            <div class="letra-card">

                <div class="letra-titulo">K</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('K', '-V1yQb33b9U')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- L -->

            <div class="letra-card">

                <div class="letra-titulo">L</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('L', 'TNEXn3RZc0Q')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- M -->

            <div class="letra-card">

                <div class="letra-titulo">M</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('M', 'bNGIXsnO-50')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- N -->

            <div class="letra-card">

                <div class="letra-titulo">N</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('N', 'uF0WXavrbls')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- O -->

            <div class="letra-card">

                <div class="letra-titulo">O</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('O', 'ZqfjkUtm-Iw')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- P -->

            <div class="letra-card">

                <div class="letra-titulo">P</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('P', 'rRCsZEcLPIE')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- Q -->

            <div class="letra-card">

                <div class="letra-titulo">Q</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('Q', 'cKDMYrdBRlU')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- R -->

            <div class="letra-card">

                <div class="letra-titulo">R</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('R', 'q2whkbF6X6Y')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- S -->

            <div class="letra-card">

                <div class="letra-titulo">S</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('S', 'nS2N9qI_suk')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- T -->

            <div class="letra-card">

                <div class="letra-titulo">T</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('T', 'x4AaudNl5j4')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- U -->

            <div class="letra-card">

                <div class="letra-titulo">U</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('U', '3sEaKyyhsc8')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- V -->

            <div class="letra-card">

                <div class="letra-titulo">V</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('V', '67JmKU7F4cw')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- W -->

            <div class="letra-card">

                <div class="letra-titulo">W</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('W', '')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- X -->

            <div class="letra-card">

                <div class="letra-titulo">X</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('X', 'hJ3msynk9Lw')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- Y -->

            <div class="letra-card">

                <div class="letra-titulo">Y</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('Y', 'dlcVqCTnfIQ')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


            <!-- Z -->

            <div class="letra-card">

                <div class="letra-titulo">Z</div>

                <div
                    class="video-card"
                    onclick="abrirVideo('Z', '2kyw0EPb0HU')">

                    <div class="play-button">
                        ▶
                    </div>

                    <div class="video-text">
                        Assistir sinal
                    </div>

                </div>

            </div>


        </section>

    </main>


    <!-- =========================
     JANELA DO VÍDEO
========================= -->

    <div
        class="video-modal"
        id="videoModal"
        onclick="fecharAoClicarFora(event)">

        <div class="video-modal-conteudo">

            <button
                class="fechar-video"
                onclick="fecharVideo()">
                ×
            </button>

            <div
                class="video-modal-titulo"
                id="videoTitulo">
                Letra A em Libras
            </div>

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
                Vídeo por: <a href="https://www.youtube.com/@incluirtecnologia" target="_blank" rel="noopener noreferrer">Canal Saber Mais</a>
            </div>

        </div>

    </div>


    <!-- =========================
     RODAPÉ
========================= -->

    <footer class="app-footer">

        <p>
            EntreGestos — Aprendendo Libras de forma divertida
        </p>

        <p class="creditos">
            Vídeos cedidos pelo canal <a href="https://www.youtube.com/@incluirtecnologia" target="_blank" rel="noopener noreferrer">Saber Mais</a>
        </p>

    </footer>


    <script>
        function abrirVideo(letra, videoId) {

            const modal = document.getElementById("videoModal");
            const iframe = document.getElementById("videoIframe");
            const titulo = document.getElementById("videoTitulo");

            titulo.textContent = "Letra " + letra + " em Libras";

            iframe.src =
                "https://www.youtube.com/embed/" +
                videoId +
                "?autoplay=1&controls=1";

            modal.classList.add("aberto");
        }


        function fecharVideo() {

            const modal = document.getElementById("videoModal");
            const iframe = document.getElementById("videoIframe");

            iframe.src = "";

            modal.classList.remove("aberto");
        }


        function fecharAoClicarFora(event) {

            if (event.target === document.getElementById("videoModal")) {
                fecharVideo();
            }

        }




        function fecharVideo() {

            const modal = document.getElementById("videoModal");

            const iframe = document.getElementById("videoIframe");

            iframe.src = "";

            modal.classList.remove("aberto");

        }


        function fecharAoClicarFora(event) {

            if (event.target === document.getElementById("videoModal")) {

                fecharVideo();

            }

        }
    </script>


</body>

</html>
