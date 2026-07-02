<?php

namespace App\model\roles;

require_once __DIR__ . '/User.php';

use AllowDynamicProperties;
use App\model\enums\AdminLevel;
use App\model\enums\UserRole;

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
