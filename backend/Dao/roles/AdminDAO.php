<?php

namespace Dao\roles;

use Dao\PDO;
use model\roles\Admin;
use Util\Conexao;

class AdminDAO {
    private PDO $conexao;
    private UserDAO $userDAO;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
        $this->userDAO = new UserDAO();
    }

    public function listar(): array {
        $sql = "SELECT * FROM admins";
        $stm = $this->conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function criar(Admin $admin): void {
        $sql = "INSERT INTO admins (admin_level) VALUES (?)";
        $stm = $this->conexao->prepare($sql);
        $stm->execute([$admin->getLevel()]);

        $this->userDAO->criar($admin);
    }

    public function deletar(int $id): void {
        $sql = "DELETE FROM admins WHERE id = ?";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$id]);
    }
}