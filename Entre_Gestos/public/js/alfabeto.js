function abrirVideo(letra, videoId, canalNome, canalUrl) {
    const modal = document.getElementById("videoModal");
    const iframe = document.getElementById("videoIframe");
    const titulo = document.getElementById("videoTitulo");

    titulo.textContent = "Letra " + letra + " em Libras";

    // crédito: nome e link do canal deste vídeo
    const canal = document.getElementById("videoCanal");
    if (canal) {
        canal.textContent = canalNome || "YouTube";
        canal.href = canalUrl || "https://www.youtube.com/";
    }
    iframe.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1&controls=1";

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

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') fecharVideo();
});
