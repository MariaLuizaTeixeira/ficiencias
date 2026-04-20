<?php

namespace service;
use repository\UserRepository;
use User;

class UserService {

    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function createUser(User $user): null
    {
        return $this->userRepository->save($user);
    }
}