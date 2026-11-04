<?php

require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../util/config.php");
require_once(__DIR__ . "/../service/UsuarioService.php");
require_once(__DIR__ . "/../util/validacao_perfil.php");

class AuthController
{
    private UsuarioDAO $usuarioDao;

    public function __construct()
    {
        $this->usuarioDao = new UsuarioDAO();
    }

    public function login(string $email, string $senha): bool
    {
        $usuario = $this->usuarioDao->findByEmail($email);

        if ($usuario && password_verify($senha, $usuario->getSenhaHash())) {
            $_SESSION['usuario_logado'] = [
                'id' => $usuario->getId(),
                'nome_completo' => $usuario->getNomeCompleto(),
                'email' => $usuario->getEmail(),
                'perfil' => $usuario->getPerfil()
            ];
            return true;
        }
        return false;
    }

    /**
     * Cria a conta de aluno e já faz o login.
     * Apelido é opcional; telefone, nascimento e gênero são obrigatórios.
     * Devolve "" se deu certo, ou a mensagem de erro.
     */
    public function cadastrar(string $nome, string $email, string $senha, array $dadosExtras = [])
    {
        if ($this->usuarioDao->findByEmail($email) !== null) {
            return "Esse e-mail já tá cadastrado! Tente recuperar a senha.";
        }

        [$extras, $erroExtras] = perfil_validar_extras($dadosExtras, true);
        if ($erroExtras !== '') {
            return $erroExtras;
        }

        $usuario = new Usuario();
        $usuario->setNomeCompleto($nome);
        $usuario->setEmail($email);
        $usuario->setSenhaHash(password_hash($senha, PASSWORD_DEFAULT));
        $usuario->setPerfil('aluno'); // Padrão é aluno
        $usuario->setEmailVerificado(true); // Deixando verificado pro usuário já sair usando

        // cria o usuário e a linha do aluno (vidas, apelido, telefone, nascimento, gênero)
        $criado = (new UsuarioService())->insert($usuario, $extras);

        if ($criado === null) {
            return "Não foi possível criar a conta. Tente novamente.";
        }

        $this->login($email, $senha); // Já loga direto
        return "";
    }

    public function logout(): void
    {
        unset($_SESSION['usuario_logado']);
        session_destroy();
    }

    public function estaLogado(): bool
    {
        return isset($_SESSION['usuario_logado']);
    }

    public function getUsuarioLogado(): ?array
    {
        return $_SESSION['usuario_logado'] ?? null;
    }

    public function getUsuarioLogadoId(): ?int
    {
        return $_SESSION['usuario_logado']['id'] ?? null;
    }

    public function exigirLogin(): void
    {
        if (!$this->estaLogado()) {
            header("Location: " . BASE_URL . "/view/auth/login.php");
            exit;
        }
    }
}
