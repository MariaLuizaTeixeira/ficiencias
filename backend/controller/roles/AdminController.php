<?php

namespace controller\roles;

use AllowDynamicProperties;
use Dao\roles\AdminDAO;
use Dao\roles\UserDAO;
use model\roles\Admin;

#[AllowDynamicProperties]
class AdminController extends UserController {
    private AdminDAO $adminDAO;

    public function __construct(){
        $this->adminDAO = new AdminDAO();
        $this->userDAO = new UserDAO();
    }

    public function listar(): array {
        return $this->adminDAO->listar();
    }

    public function criar(Admin $admin): void {
        $this->adminDAO->criar($admin);
    }

    public function deletar(int $id): void {
        $this->adminDAO->deletar($id);
    }

    /*
    public function editar(int $id): void {
        $this->adminService->editar($id);
    }
    */
}