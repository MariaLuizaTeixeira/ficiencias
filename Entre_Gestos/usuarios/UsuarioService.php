<?php

require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../model/Aluno.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/AlunoDAO.php");

class UsuarioService
{

    public function validar(Usuario $usuario) {}

    /**
     * @param array $extras apelido, numero_telefone, data_nascimento, genero (todos opcionais)
     */
    public function insert(Usuario $usuario, array $extras = [])
    {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->insert($usuario);
        if ($usuario === null) {
            return null; // o DAO já mostrou o erro (ex.: e-mail repetido)
        }

        $aluno = new Aluno();
        $aluno->setIdUsuario($usuario->getId());
        $aluno->setVidas(5);
        $aluno->setSequencia(0);
        $aluno->setApelido($extras['apelido'] ?? null);
        $aluno->setNumeroTelefone($extras['numero_telefone'] ?? null);
        $aluno->setDataNascimento($extras['data_nascimento'] ?? null);
        $aluno->setGenero($extras['genero'] ?? null);

        $alunoDAO = new AlunoDAO();
        $alunoDAO->insert($aluno);

        return $usuario;
    }

    /** Atualiza apelido, telefone, nascimento e gênero de um usuário já existente. */
    public function atualizarExtras(int $idUsuario, array $extras): void
    {
        (new AlunoDAO())->salvarExtras($idUsuario, $extras);
    }
}
