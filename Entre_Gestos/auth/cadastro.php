<?php
require_once(__DIR__ . "/../../controller/AuthController.php");
require_once(__DIR__ . "/../../util/validacao_perfil.php");

$authController = new AuthController();

if ($authController->estaLogado()) {
    header("Location: ../app/trilha.php");
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($senha !== $confirmar_senha) {
        $erro = 'Uai, as senhas não tão batendo! Dá uma conferida aí, sô.';
    } else {
        $resultado = $authController->cadastrar($nome, $email, $senha, [
            'apelido'         => $_POST['apelido'] ?? '',
            'numero_telefone' => $_POST['numero_telefone'] ?? '',
            'data_nascimento' => $_POST['data_nascimento'] ?? '',
            'genero'          => $_POST['genero'] ?? '',
        ]);

        if (empty($resultado)) {
            header("Location: ../app/trilha.php");
            exit;
        } else {
            $erro = $resultado;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - EntreGestos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="../../public/css/global.css">
   <link rel="stylesheet" href="../../public/css/cadastro.css">
  
</head>

<body>

    <div class="split-layout">
        <!-- Esquerda: Capivara / Mensagem -->
        <div class="left-side">
            <div class="mascote">
                <img src="<?= BASE_URL ?>/view/assets/imagens/capivara.png" alt="Capivara">
            </div>
            <h1>Aprenda Libras com a Capivara!</h1>
            <p>Sua jornada de aprendizado começa aqui 🌱</p>
        </div>

        <!-- Direita: Card de Cadastro -->
        <div class="right-side">
            <div class="login-card">
                <h2>Criar sua conta</h2>
                <p>Venha fazer parte dessa comunidade</p>

                <?php if (!empty($erro)): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Nome Completo</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" name="nome" class="form-control" placeholder="Seu Nome" maxlength="64" required autofocus value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input type="email" name="email" class="form-control" placeholder="seu@email.com" maxlength="64" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Apelido <small>(opcional)</small></label>
                        <div class="input-wrapper">
                            <span class="input-icon">😊</span>
                            <input type="text" name="apelido" class="form-control" placeholder="Como quer ser chamado(a)" maxlength="16" value="<?= htmlspecialchars($_POST['apelido'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Telefone</label>
                        <div class="input-wrapper">
                            <span class="input-icon">📱</span>
                            <input type="tel" name="numero_telefone" id="telefone" class="form-control" placeholder="(45) 99999-9999" maxlength="15" inputmode="numeric" required value="<?= htmlspecialchars($_POST['numero_telefone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nascimento</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🎂</span>
                            <input type="date" name="data_nascimento" class="form-control" min="1900-01-01" max="<?= date('Y-m-d') ?>" required value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gênero</label>
                        <div class="input-wrapper">
                            <span class="input-icon">⚧️</span>
                            <select name="genero" class="form-control" required>
                                <option value="" disabled <?= empty($_POST['genero']) ? 'selected' : '' ?>>Selecione</option>
                                <?php foreach (perfil_generos() as $g): ?>
                                    <option value="<?= htmlspecialchars($g) ?>" <?= ($_POST['genero'] ?? '') === $g ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirme a Senha</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔑</span>
                            <input type="password" name="confirmar_senha" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-main">Cadastrar</button>
                </form>

                <div class="divider"></div>

                <div class="footer-link">
                    Já tem uma conta? <a href="login.php">Fazer Login</a>
                </div>
            </div>
        </div>
    </div>

<script>
    // máscara simples do telefone: (45) 99999-9999
    (function () {
        var t = document.getElementById('telefone');
        if (!t) return;
        t.addEventListener('input', function () {
            var n = t.value.replace(/\D/g, '').slice(0, 11);
            var f = n;
            if (n.length > 10)      f = '(' + n.slice(0, 2) + ') ' + n.slice(2, 7) + '-' + n.slice(7);
            else if (n.length > 6)  f = '(' + n.slice(0, 2) + ') ' + n.slice(2, 6) + '-' + n.slice(6);
            else if (n.length > 2)  f = '(' + n.slice(0, 2) + ') ' + n.slice(2);
            else if (n.length > 0)  f = '(' + n;
            t.value = f;
        });
    })();
</script>

</body>

</html>