<?php

namespace service;
use repository\UserRepository;

class UserService {

    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function createUser(mixed $data): null {
        return $this->userRepository->save($data);
    }
}