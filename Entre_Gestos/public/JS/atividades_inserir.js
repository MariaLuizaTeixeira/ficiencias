document.addEventListener("DOMContentLoaded", function () {

    const telaTipo = document.getElementById("tela-tipo");
    const telaForm = document.getElementById("tela-form");
    const inputTipoInteracao = document.getElementById("input_tipo_interacao");
    const resumoTela2 = document.getElementById("resumoTela2");
    const indicador   = document.getElementById("indicadorPassos");
    const paresContainer = document.getElementById("paresContainer");
    const qtdParesInput  = document.getElementById("qtd_pares");
    const btnAddPar      = document.getElementById("btnAddPar");

    const rotulosInteracao = {
        options:     "☑ Múltipla Escolha",
        association: "🔗 Associativa",
        open:        "✍ Escrita"
    };

    let tipoSelecionado = null;
    let qtdPares = 0;

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

    // ── Seleção do tipo (Tela 1 → Tela 2) ─────────────────────────────
    telaTipo.querySelectorAll(".tipo-card").forEach(btn => {
        btn.addEventListener("click", function () {
            tipoSelecionado = this.dataset.valor;

            // Atualiza o campo oculto (hidden)
            inputTipoInteracao.value = tipoSelecionado;

            // Resumo (tag)
            resumoTela2.innerHTML = "";
            const tag = document.createElement("span");
            tag.className = "resumo-tag";
            tag.innerHTML = rotulosInteracao[tipoSelecionado] + " ";
            const trocar = document.createElement("button");
            trocar.type = "button";
            trocar.textContent = "trocar";
            trocar.addEventListener("click", () => mostrarTela("tela-tipo"));
            tag.appendChild(trocar);
            resumoTela2.appendChild(tag);

            // Mostra o bloco correto
            document.querySelectorAll(".bloco-tipo").forEach(b => b.style.display = "none");
            const bloco = document.getElementById("bloco-" + tipoSelecionado);
            if (bloco) bloco.style.display = "block";

            // Inicializa pares se associativa
            if (tipoSelecionado === "association" && qtdPares === 0) {
                for (let i = 0; i < 4; i++) adicionarPar();
            }

            mostrarTela("tela-form");
        });
    });

    // ── Botão Voltar (Tela 2 → Tela 1) ────────────────────────────────
    document.getElementById("btnVoltar").addEventListener("click", () => {
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

    // Inicia na tela 1
    mostrarTela("tela-tipo");
});
