<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Models\Enums\AdminLevel;
use App\Models\Enums\UserRole;

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
