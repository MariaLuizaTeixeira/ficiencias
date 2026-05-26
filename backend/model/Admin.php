<?php

namespace Model;

use AllowDynamicProperties;
use Model\enums\AdminLevel;
use Model\enums\UserRole;

#[AllowDynamicProperties]
class Admin extends User {
    private AdminLevel $level;

    public function __construct() {
        $this->userType = UserRole::ADMIN;
    }

    public function getLevel(): AdminLevel
    {
        return $this->level;
    }

    public function setLevel(AdminLevel $level): void
    {
        $this->level = $level;
    }
}
