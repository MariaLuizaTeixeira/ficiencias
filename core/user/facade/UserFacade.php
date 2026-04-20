<?php

namespace facade;
use dto\UserFormDTO;
use service\UserService;
use User;

class UserFacade {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    function createUser(UserFormDTO $userFormDTO): User {
        $required = ['full_name', 'email', 'nickname', 'gender', 'birth_date', 'phone_number'];

        $missing = array_diff($required, array_keys((array)$userFormDTO));

        if ($missing) {
            http_response_code(400);
            return json_encode([
                "error" => "Missing required fields",
                "fields" => array_values($missing)
            ]);
        }

        $this->userService->createUser($user);
        return $user;
    }
}