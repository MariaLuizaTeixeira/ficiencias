<?php
/**
 * PERFIL — lógica compartilhada (atualizar dados e excluir conta).
 * Usada pela página de perfil do aluno e pelo painel do admin.
 *
 * Antes de incluir, defina:
 *   $perfilUrl = 'perfil.php';   // página que recebe o formulário (sem query string)
 *
 * Depois de incluir, ficam disponíveis:
 *   $id, $admin, $u (usuário), $a (dados do aluno), $msg, $erro, $editando,
 *   $feitas, $total, $pct (progresso) e as funções h() e vazio().
 *
 * O id vem sempre da sessão: ninguém altera o perfil de outra pessoa por aqui.
 * Precisa rodar ANTES de qualquer saída HTML (pode redirecionar).
 */
require_once(__DIR__ . "/../../../util/config.php");
require_once(__DIR__ . "/../../../util/Connection.php");
require_once(__DIR__ . "/../../../util/iniciais.php");
require_once(__DIR__ . "/../../../util/validacao_perfil.php");

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: " . BASE_URL . "/view/auth/login.php");
    exit;
}

if (!function_exists('h')) {
    // escapa texto para exibir com segurança no HTML
    function h($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('vazio')) {
    // mostra "não informado" quando o campo está em branco
    function vazio($v) { return $v !== null && $v !== '' ? h($v) : '<i>não informado</i>'; }
}

$id    = (int) $_SESSION['usuario_logado']['id'];
$admin = ($_SESSION['usuario_logado']['perfil'] === 'administrador');
$pdo   = Connection::getConnection();

// token anti-CSRF dos formulários
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

$msg = ''; $erro = '';
$editando = isset($_GET['editar']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) die("Requisição inválida.");

    $st = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $st->execute([$id]);
    $atual = $st->fetch(PDO::FETCH_ASSOC);

    // ---------- ATUALIZAR ----------
    if (($_POST['acao'] ?? '') === 'salvar') {
        $nome  = trim($_POST['nome_completo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        // apelido, telefone, nascimento e gênero (aluno e administrador); aqui nada é obrigatório
        [$extras, $erroExtras] = perfil_validar_extras($_POST, false);

        // outro usuário já usa esse e-mail?
        $st = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id <> ?");
        $st->execute([$email, $id]);

        if ($nome === '' || strlen($nome) > 64)               $erro = "Informe seu nome.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))   $erro = "E-mail inválido.";
        elseif ($st->fetch())                                 $erro = "Esse e-mail já está em uso.";
        elseif ($senha !== '' && strlen($senha) < 6)          $erro = "A senha precisa ter 6 ou mais caracteres.";
        elseif ($erroExtras !== '')                           $erro = $erroExtras;
        else {
            // o tipo de conta (aluno/admin) NUNCA é alterado aqui
            $pdo->prepare("UPDATE usuarios SET nome_completo = ?, email = ? WHERE id = ?")->execute([$nome, $email, $id]);
            if ($senha !== '')
                $pdo->prepare("UPDATE usuarios SET senha_hash = ? WHERE id = ?")->execute([password_hash($senha, PASSWORD_DEFAULT), $id]);
            // grava os dados extras (cria a linha em alunos se o usuário ainda não tiver)
            $stA = $pdo->prepare("SELECT id FROM alunos WHERE id_usuario = ?");
            $stA->execute([$id]);
            if ($stA->fetch()) {
                $pdo->prepare("UPDATE alunos SET apelido = ?, numero_telefone = ?, data_nascimento = ?, genero = ? WHERE id_usuario = ?")
                    ->execute([$extras['apelido'], $extras['numero_telefone'], $extras['data_nascimento'], $extras['genero'], $id]);
            } else {
                $pdo->prepare("INSERT INTO alunos (id_usuario, vidas, sequencia, apelido, numero_telefone, data_nascimento, genero) VALUES (?, 5, 0, ?, ?, ?, ?)")
                    ->execute([$id, $extras['apelido'], $extras['numero_telefone'], $extras['data_nascimento'], $extras['genero']]);
            }
            $_SESSION['usuario_logado']['nome_completo'] = $nome;
            $_SESSION['usuario_logado']['email'] = $email;
            $msg = "Perfil atualizado!";
            $editando = false;
        }
        if ($erro) $editando = true;
    }

    // ---------- EXCLUIR (só aluno) ----------
    if (($_POST['acao'] ?? '') === 'excluir') {
        if ($admin)                                                                      $erro = "Administrador não exclui a própria conta aqui.";
        elseif (!password_verify($_POST['senha_confirmar'] ?? '', $atual['senha_hash'])) $erro = "Senha incorreta. Nada foi excluído.";
        else {
            $pdo->beginTransaction();
            foreach (['progresso_licoes', 'progresso_questao', 'respostas'] as $t)
                $pdo->prepare("DELETE FROM $t WHERE aluno_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM alunos WHERE id_usuario = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);
            $pdo->commit();
            session_destroy();
            header("Location: " . BASE_URL . "/view/auth/login.php");
            exit;
        }
    }
}

// ---------- DADOS PARA EXIBIR ----------
$st = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$st->execute([$id]);
$u = $st->fetch(PDO::FETCH_ASSOC);
if (!$u) { session_destroy(); header("Location: " . BASE_URL . "/view/auth/login.php"); exit; }

$st = $pdo->prepare("SELECT * FROM alunos WHERE id_usuario = ?");
$st->execute([$id]);
$a = $st->fetch(PDO::FETCH_ASSOC) ?: [];

// progresso nas lições
$st = $pdo->prepare("SELECT COUNT(*) FROM progresso_licoes WHERE aluno_id = ? AND completada = 1");
$st->execute([$id]);
$feitas = (int) $st->fetchColumn();
$total  = (int) $pdo->query("SELECT COUNT(*) FROM licoes")->fetchColumn();
$pct    = $total > 0 ? min(100, round($feitas / $total * 100)) : 0;
