<?php

require_once(__DIR__ . "/../util/Connection.php");
require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../model/Aluno.php");
require_once(__DIR__ . "/../dao/AlunoDAO.php");

class UsuarioDAO
{

    private PDO $connection;
    private AlunoDAO $alunoDAO;

    public function __construct()
    {
        $this->connection = Connection::getConnection();
        $this->alunoDAO = new AlunoDAO();
    }

    public function list()
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome_completo";

        $stm = $this->connection->prepare($sql);
        $stm->execute();

        $dados = $stm->fetchAll();
        return $this->map($dados);
    }

    public function findByEmail(string $email): ?Usuario
    {
        $sql = "SELECT * FROM usuarios WHERE email = ?";

        $stm = $this->connection->prepare($sql);
        $stm->execute([$email]);

        $dados = $stm->fetchAll();
        $usuarios = $this->map($dados);
        if (!empty($usuarios))
            return $usuarios[0];
        else
            return NULL;
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";

        $conn = Connection::getConnection();
        $stm = $conn->prepare($sql);
        $stm->execute([$id]);

        $dados = $stm->fetchAll();
        $usuarios = $this->map($dados);
        if (!empty($usuarios))
            return $usuarios[0];
        else
            return NULL;
    }

    public function insert(Usuario $usuario): ?Usuario
    {
        try {
            $sql = "INSERT INTO usuarios (nome_completo, email, senha_hash, email_verificado, perfil)
                    VALUES (:nome, :email, :senha, :email_verificado, :perfil)";

            $stm = $this->connection->prepare($sql);
            $stm->bindValue("nome", $usuario->getNomeCompleto());
            $stm->bindValue("email", $usuario->getEmail());
            $stm->bindValue("senha", $usuario->getSenhaHash());
            $stm->bindValue("email_verificado", $usuario->getEmailVerificado() ? 1 : 0, PDO::PARAM_INT);
            $stm->bindValue("perfil", $usuario->getPerfil());

            $stm->execute();

            $usuario->setId((int) $this->connection->lastInsertId());

            return $usuario;
        } catch (PDOException $e) {
            $erro = "Erro ao salvar o usuário. Tente novamente.";
            if (AMB_DEV)
                $erro .= "<br>" . $e->getMessage();
            print $erro;

            return null;
        }
    }

    public function update(Usuario $usuario)
    {
        try {
            $sql = "UPDATE usuarios
                    SET nome_completo = :nome, email = :email, email_verificado = :email_verificado, perfil = :perfil
                    WHERE id = :id";

            $stm = $this->connection->prepare($sql);
            $stm->bindValue("nome", $usuario->getNomeCompleto());
            $stm->bindValue("email", $usuario->getEmail());
            $stm->bindValue("email_verificado", $usuario->getEmailVerificado() ? 1 : 0, PDO::PARAM_INT);
            $stm->bindValue("perfil", $usuario->getPerfil());
            $stm->bindValue("id", $usuario->getId());
            $stm->execute();
            return "";
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar o usuário. Tente novamente.";
            if (AMB_DEV)
                $erro .= "<br>" . $e->getMessage();
            return $erro;
        }
    }

    public function excluir(int $id)
    {
        try {
            $sql = "DELETE FROM usuarios WHERE id = ?";

            $stm = $this->connection->prepare($sql);
            $stm->execute([$id]);
            return "";
        } catch (PDOException $e) {
            $erro = "Erro ao excluir o usuário. Tente novamente.";
            if (AMB_DEV)
                $erro .= "<br>" . $e->getMessage();
            return $erro;
        }
    }

    private function map(array $dados)
    {
        $usuarios = array();
        foreach ($dados as $d) {
            $usuario = new Usuario();
            $usuario->setId($d['id']);
            $usuario->setNomeCompleto($d["nome_completo"]);
            $usuario->setEmail($d["email"]);
            $usuario->setSenhaHash($d["senha_hash"]);
            $usuario->setCriadoEm($d["criado_em"]);
            $usuario->setAtualizadoEm($d["atualizado_em"]);
            $usuario->setEmailVerificado((bool) $d["email_verificado"]);
            $usuario->setPerfil($d["perfil"]);

            array_push($usuarios, $usuario);
        }
        return $usuarios;
    }
}
