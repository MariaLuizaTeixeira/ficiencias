document.addEventListener("DOMContentLoaded", function () {

    const telaTipo = document.getElementById("tela-tipo");
    const telaForm = document.getElementById("tela-form");
    const indicador   = document.getElementById("indicadorPassos");
    const paresContainer = document.getElementById("paresContainer");
    const qtdParesInput  = document.getElementById("qtd_pares");
    const btnAddPar      = document.getElementById("btnAddPar");

    let qtdPares = parseInt(qtdParesInput.value) || 4;

    // ── Navegação entre telas ──────────────────────────────────────────
    function mostrarTela(id) {
        [telaTipo, telaForm].forEach(t => t.classList.remove("ativa"));
        document.getElementById(id).classList.add("ativa");

        const mapa = { "tela-tipo": 1, "tela-form": 2 };
        const atual = mapa[id];
        indicador.querySelectorAll(".wizard-step").forEach(el => {
            const n = parseInt(el.dataset.passo, 10);
            el.classList.remove("ativo", "concluido");
            if (n === atual) el.classList.add("ativo");
            else if (n < atual) el.classList.add("concluido");
        });
    }

    document.getElementById("btnAvancar").addEventListener("click", () => {
        mostrarTela("tela-form");
    });

    // Na edição começamos direto no passo 2 (Formulário) para economizar tempo, 
    // mas o usuário pode voltar para ver o tipo se quiser.
    mostrarTela("tela-form");

    // ── Botão Voltar (Tela 2 → Tela 1) ────────────────────────────────
    document.getElementById("btnVoltar").addEventListener("click", () => {
        mostrarTela("tela-tipo");
    });

    // ── Botão Trocar na tag de resumo (mostrado em inserir, oculto aqui, mas tem o botão)
    document.getElementById("btnTrocar").addEventListener("click", () => {
        mostrarTela("tela-tipo");
    });

    // ── Pares associativos ─────────────────────────────────────────────
    function adicionarPar() {
        qtdPares++;
        qtdParesInput.value = qtdPares;

        const div = document.createElement("div");
        div.className = "par-linha";
        div.innerHTML = `
            <input type="text" name="par_${qtdPares}_esquerdo" class="form-control"
                   placeholder="Alternativa ${qtdPares}…">
            <div class="par-seta">→</div>
            <input type="text" name="par_${qtdPares}_direito" class="form-control"
                   placeholder="Resposta ${qtdPares}…">
        `;
        paresContainer.appendChild(div);
    }

    btnAddPar.addEventListener("click", adicionarPar);
});
