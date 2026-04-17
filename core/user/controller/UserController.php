<?php

use facade\UserFacade;
use response\Response;

class UserController {
    private UserFacade $userFacade;
    private Response $response;

    public function __construct() {
        $this->userFacade = new UserFacade();
        $this->response = new Response();
    }
    function createUser(\dto\UserFormDTO $userFormDTO): string {
        print("Create user");
        return $this->response::created($this->userFacade->createUser($userFormDTO));
    }
}