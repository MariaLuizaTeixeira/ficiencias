const tipoQuestao = LICAO_CONFIG.tipoQuestao;
let selectedBtn = null;

let perdendoVida = false;   // true enquanto o servidor ainda não respondeu (evita tirar 2 vidas)
let timerAnimo = null;      // troca sozinha do choro para o ânimo

let respondida = false;     // true depois de VERIFICAR: nada muda até apertar "TENTE DE NOVO"

function selectOption(btn) {
    if (respondida || btn.disabled) return;   // depois de errar, só "TENTE DE NOVO" libera
    document.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedBtn = btn;
    document.getElementById('checkBtn').classList.add('active');
    document.getElementById('checkBtn').disabled = false;
}

function checkInput() {
    const val = document.getElementById('resposta_aberta').value.trim();
    const btn = document.getElementById('checkBtn');
    if (val.length > 0) {
        btn.classList.add('active');
        btn.disabled = false;
    } else {
        btn.classList.remove('active');
        btn.disabled = true;
    }
}

function checkAssoc() {
    const selects = document.querySelectorAll('.assoc-right');
    let allFilled = true;
    selects.forEach(s => {
        if (s.value === "") allFilled = false;
    });
    const btn = document.getElementById('checkBtn');
    if (allFilled) {
        btn.classList.add('active');
        btn.disabled = false;
    } else {
        btn.classList.remove('active');
        btn.disabled = true;
    }
}

function checkAnswer() {
    const checkBtn  = document.getElementById('checkBtn');
    if (checkBtn.dataset.modo === 'tentar') { tentarDeNovo(); return; }
    if (checkBtn.dataset.modo === 'trilha') { window.location.href = LICAO_CONFIG.trilhaUrl; return; }
    if (checkBtn.dataset.modo === 'proxima') { window.location.href = checkBtn.dataset.destino; return; }
    if (checkBtn.disabled || respondida) return;

    let isCorrect = false;

    if (tipoQuestao === 'multipla_escolha') {
        if (!selectedBtn) return;
        respondida = true;
        // trava todas as opções: certa ou errada, não dá para mexer até "TENTE DE NOVO"
        document.querySelectorAll('.option-btn').forEach(b => { b.disabled = true; });
        isCorrect = selectedBtn.getAttribute('data-correct') === '1';
        if (isCorrect) {
            selectedBtn.classList.add('correct');
            selectedBtn.classList.remove('selected');
        } else {
            selectedBtn.classList.add('wrong');
            selectedBtn.classList.remove('selected');
        }
    } else if (tipoQuestao === 'aberta') {
        const answer = document.getElementById('resposta_aberta').value.trim().toLowerCase();
        const correct = document.getElementById('correct_answer').value.trim().toLowerCase();
        isCorrect = (answer === correct);
        respondida = true;
        const inputEl = document.getElementById('resposta_aberta');
        if (isCorrect) {
            inputEl.style.borderColor = 'var(--green-main)';
            inputEl.style.color = 'var(--green-shadow)';
            inputEl.disabled = true;
        } else {
            inputEl.style.borderColor = '#ea2b2b';
            inputEl.style.color = '#ea2b2b';
            inputEl.disabled = true;
        }
    } else if (tipoQuestao === 'associacao') {
        const rows = document.querySelectorAll('.assoc-row');
        isCorrect = true;
        respondida = true;
        rows.forEach(row => {
            const select = row.querySelector('.assoc-right');
            const correctVal = row.getAttribute('data-correct');
            if (select.value !== correctVal) {
                isCorrect = false;
                select.style.borderColor = '#ea2b2b';
                select.style.color = '#ea2b2b';
                select.disabled = true;           // travada até "TENTE DE NOVO"
                select.dataset.erro = '1';
            } else {
                select.style.borderColor = 'var(--green-main)';
                select.style.color = 'var(--green-shadow)';
                select.disabled = true;
            }
        });
    }

    const feedback  = document.getElementById('feedback');
    const footer    = document.getElementById('footer');

    if (isCorrect) {
        footer.style.backgroundColor = '#d7ffb8';
        footer.style.borderColor     = '#d7ffb8';
        feedback.style.display       = 'block';
        feedback.style.color         = 'var(--green-shadow)';
        feedback.innerText           = '✓ Certo!';

        // botão único: segue para a próxima questão ou conclui a lição
        checkBtn.innerText = 'PRÓXIMA ATIVIDADE';
        checkBtn.classList.add('active');
        checkBtn.dataset.modo = 'proxima';
        checkBtn.dataset.destino = LICAO_CONFIG.proximaUrl || LICAO_CONFIG.concluirUrl;
        mostrarCapivara(LICAO_CONFIG.imagens.vitoria, 'Mandou bem!');

        document.querySelector('.progress-fill').style.width = LICAO_CONFIG.progressoPercentual + '%';
    } else {
        footer.style.backgroundColor = '#ffdfe0';
        footer.style.borderColor     = '#ffdfe0';
        feedback.style.display       = 'block';
        feedback.style.color         = '#ea2b2b';
        feedback.innerText           = '✗ Errado';

        errou(); // só aqui, no ramo de erro: tira a vida e mostra a capivara
    }
}


/* =====================================================================
   VIDAS
   ===================================================================== */

// Pede ao servidor para tirar 1 vida. Devolve as vidas que sobraram, ou null se falhou.
function perderVida() {
    return fetch(LICAO_CONFIG.urlPerderVida, { method: 'POST' })
        .then(resposta => resposta.json())
        .then(dados => {
            if (!dados.ok) return null;
            const contador = document.getElementById('vidas');
            contador.innerText = dados.vidas;
            // o coração dá uma batida
            contador.classList.remove('vidas-balanco');
            void contador.offsetWidth; // reinicia a animação
            contador.classList.add('vidas-balanco');
            return dados.vidas;
        })
        .catch(() => null);
}

// Chamada quando o aluno erra: capivara chora, depois anima, e o botão vira "TENTE DE NOVO".
function errou() {
    if (perdendoVida) return;           // segurança extra contra clique duplo
    perdendoVida = true;

    const checkBtn = document.getElementById('checkBtn');
    checkBtn.disabled = true;           // até o servidor responder
    checkBtn.classList.add('active');
    checkBtn.innerText = 'TENTE DE NOVO';

    // 1) capivara chorando; 2) uns 2 segundos depois, ela troca para a de ânimo
    mostrarCapivara(LICAO_CONFIG.imagens.derrota, 'Não foi dessa vez');
    clearTimeout(timerAnimo);
    timerAnimo = setTimeout(() => mostrarCapivara(LICAO_CONFIG.imagens.motivacao, 'Não desista! Tente de novo'), 2000);

    perderVida().then(vidas => {
        perdendoVida = false;
        if (vidas === 0) {
            // sem vidas: não deixa continuar errando
            document.getElementById('feedback').innerText = '✗ Errado · sem vidas. Volte com as vidas completas';
            checkBtn.innerText = 'VOLTAR À TRILHA';
            checkBtn.dataset.modo = 'trilha';
        } else {
            checkBtn.dataset.modo = 'tentar';
        }
        checkBtn.disabled = false;
    });
}

// Limpa a resposta errada e deixa o aluno tentar outra vez, com a transição suave.
function tentarDeNovo() {
    clearTimeout(timerAnimo);
    const checkBtn = document.getElementById('checkBtn');
    const footer   = document.getElementById('footer');
    const feedback = document.getElementById('feedback');

    // múltipla escolha: a opção errada fica marcada e travada; ela escolhe entre as outras
    document.querySelectorAll('.option-btn.wrong').forEach(b => {
        b.classList.remove('wrong');
        b.classList.add('descartada');
        b.disabled = true;
    });
    // as demais opções voltam a poder ser escolhidas
    document.querySelectorAll('.option-btn:not(.descartada)').forEach(b => { b.disabled = false; });
    selectedBtn = null;
    respondida = false;

    const inputEl = document.getElementById('resposta_aberta');
    if (inputEl) {
        inputEl.disabled = false;
        inputEl.style.borderColor = '';
        inputEl.style.color = '';
        inputEl.focus();
    }
    // associação: as linhas certas continuam preenchidas e travadas; só as erradas são limpas
    document.querySelectorAll('.assoc-right').forEach(sel => {
        if (!sel.dataset.erro) return;      // as certas continuam travadas
        delete sel.dataset.erro;
        sel.disabled = false;
        sel.style.borderColor = '';
        sel.style.color = '';
        sel.value = '';
    });

    footer.style.backgroundColor = '';
    footer.style.borderColor = '';
    feedback.style.display = 'none';
    esconderCapivara();

    delete checkBtn.dataset.modo;
    checkBtn.innerText = 'VERIFICAR';
    checkBtn.classList.remove('active');
    checkBtn.disabled = true;
    if (inputEl) checkInput();
    if (document.querySelector('.assoc-right')) checkAssoc();
}

/* =====================================================================
   CAPIVARA AO LADO DA PERGUNTA
   ===================================================================== */

function mostrarCapivara(src, fala) {
    const img = document.getElementById('capivaraLado');
    const balao = document.getElementById('capivaraBalao');

    function pôr() {
        img.src = src;
        img.className = 'capivara-lado entra';
        balao.innerText = fala;
        balao.className = 'capivara-balao entra';
        balao.hidden = false;
    }

    if (img.hidden) {
        img.hidden = false;
        void img.offsetWidth;
        pôr();
        return;
    }
    // já está na tela: some devagar, troca a imagem e a fala, e volta (sem "piscar")
    img.classList.add('troca');
    balao.classList.add('troca');
    setTimeout(pôr, 220);
}

function esconderCapivara() {
    const img = document.getElementById('capivaraLado');
    const balao = document.getElementById('capivaraBalao');
    if (img.hidden) return;
    img.classList.add('sai');
    balao.classList.add('sai');
    setTimeout(() => {
        img.hidden = true; img.className = 'capivara-lado';
        balao.hidden = true; balao.className = 'capivara-balao';
    }, 250);
}

// carrega as imagens antes, para a camada não aparecer vazia
Object.values(LICAO_CONFIG.imagens).forEach(src => { new Image().src = src; });
