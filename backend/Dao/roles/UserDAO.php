<?php

namespace Dao\roles;

use model\roles\User;
use PDO;
use Util\Conexao;

class UserDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function listar(): array {
        $sql = "SELECT * FROM users";
        $stm = $this->conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function criar(User $user): void {
        $sql = "INSERT INTO users (full_name, email, password_hash, created_at, updated_at, email_verified, role) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$user->getFullName(), $user->getEmail(), $user->getPasswordHash(), $user->getCreatedAt(), $user->getUpdatedAt(), $user->isEmailVerified(), $user->getRole()]);
    }

    public function deletar(int $id): void {
        $sql = "DELETE FROM users WHERE id = ?";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$id]);
    }
}