<?php

namespace controller\roles;

use AllowDynamicProperties;
use Dao\roles\StudentDAO;
use Dao\roles\UserDAO;
use model\roles\Student;

#[AllowDynamicProperties]
class StudentController extends UserController {
    private StudentDAO $studentDAO;

    public function __construct() {
        $this->studentDAO = new StudentDAO();
        $this->userDAO = new UserDAO();
    }

    public function listar(): array {
        return $this->studentDAO->listar();
    }

    public function criar(Student $student): void {
        $this->studentDAO->criar($student);
    }

    public function deletar(int $id): void {
        $this->studentDAO->deletar($id);
    }

    /*
    public function editar(int $id): void {
        $this->studentService->editar($id);
    }
    */
}