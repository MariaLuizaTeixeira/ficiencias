<?php
require_once(__DIR__ . "/../../controller/AuthController.php");

$authController = new AuthController();

if ($authController->estaLogado()) {
    header("Location: ../app/trilha.php");
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($authController->login($email, $senha)) {
        header("Location: ../app/trilha.php");
        exit;
    } else {
        $erro = 'Uai, e-mail ou senha tão errados! Tenta de novo, sô.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - EntreGestos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/login.css">
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

        <!-- Direita: Card de Login -->
        <div class="right-side">
            <div class="login-card">
                <h2>Bem-vindo de volta!</h2>
                <p>Entre para continuar aprendendo</p>

                <div class="dados">
                    <p>Use os dados abaixo para entrar:</p>
                    <p>Email: aluno@entregestos.com</p>
                    <p>Senha: 123456</p>

                    <p>Use os dados abaixo para entrar:</p>
                    <p>Email: admin@entregestos.com</p>
                    <p>Senha: 123</p>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input type="email" name="email" class="form-control" placeholder="seu@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <a href="#" class="forgot-password">Esqueceu a senha?</a>

                    <button type="submit" class="btn-main">→ Entrar</button>
                    <!-- Funcionalidade de visitante pode redirecionar pra uma trilha "aberta" ou exigir login fake -->
                    <button type="button" class="btn-secondary" onclick="alert('Uai, ainda tamo construindo a área de visitantes!');">Entrar como Visitante</button>
                </form>

                <div class="divider"></div>

                <div class="footer-link">
                    Não tem uma conta? <a href="cadastro.php">Criar conta</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
