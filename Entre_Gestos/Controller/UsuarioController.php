<?php

require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../service/UsuarioService.php");
require_once(__DIR__ . "/../dao/AlunoDAO.php");
require_once(__DIR__ . "/../util/validacao_perfil.php");


class UsuarioController
{
    private UsuarioService $usuarioService;
    private UsuarioDAO $usuarioDao;

    public function __construct()
    {
        $this->usuarioService = new UsuarioService();
        $this->usuarioDao = new UsuarioDAO();
    }

    public function listar()
    {
        return $this->usuarioDao->list();
    }

    public function buscarPorId(int $id)
    {
        return $this->usuarioDao->findById($id);
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $usuario->setId($_POST['id']);
            }

            $usuario->setNomeCompleto($_POST['nome_completo']);
            $usuario->setEmail($_POST['email']);
            $usuario->setPerfil($_POST['perfil']);
            $usuario->setEmailVerificado(isset($_POST['email_verificado']));

            // Apenas para exemplo simples, senha_hash sendo um md5. No mundo real use password_hash.
            if (empty($usuario->getId()) && isset($_POST['senha'])) {
                $usuario->setSenhaHash(password_hash($_POST['senha'], PASSWORD_DEFAULT));
            }

            // campos extras (ficam na tabela alunos): apelido, telefone, nascimento, gênero
            // (vale para aluno e para administrador; aqui nada é obrigatório)
            [$extras, $erroExtras] = perfil_validar_extras($_POST, false);

            if (empty($usuario->getId())) {
                $erro = $this->usuarioService->insert($usuario, $extras);
            } else {
                $erro = $this->usuarioDao->update($usuario);
                if ($erro === "") {
                    $this->usuarioService->atualizarExtras((int) $usuario->getId(), $extras);
                }
            }

            header("Location: listar.php");
            exit;
        }
    }

    public function excluir()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $erro = $this->usuarioDao->excluir($id);
            if (empty($erro)) {
                header("Location: listar.php");
                exit;
            } else {
                echo "<p>Erro: $erro</p>";
            }
        }
    }
}
