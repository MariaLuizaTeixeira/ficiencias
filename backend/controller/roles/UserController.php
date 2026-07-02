<?php

namespace controller\roles;
use App\Dao\roles\UserDAO;

class UserController {
    private UserDAO $userDAO;

    public function __construct(){
        $this->userDAO = new UserDAO();
    }
}
