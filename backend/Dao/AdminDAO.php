<?php

namespace Dao;

use Model\Admin;
use Util\Conexao;

class AdminDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function listar(): array {
        $sql = "SELECT * FROM admins";
        $stm = $this->conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function criar(Admin $admin): void {
        $sql = "INSERT INTO admins (id, admin_level) VALUES (?, ?)";
        $stm = $this->conexao->prepare($sql);
        $stm->execute([$admin->getId(), $admin->getLevel()]);
    }

    public function deletar(int $id): void {
        $sql = "DELETE FROM admins WHERE id = ?";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$id]);
    }
}