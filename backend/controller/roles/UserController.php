<?php

namespace controller\roles;
use Dao\roles\UserDAO;

class UserController {
    private UserDAO $userDAO;

    public function __construct(){
        $this->userDAO = new UserDAO();
    }
}
