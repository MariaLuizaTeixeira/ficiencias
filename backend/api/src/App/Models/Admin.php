<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Models\Enum\AdminLevelType;
use App\Models\Enum\RoleType;

#[AllowDynamicProperties]
class Admin extends User {
    // attributes
    private AdminLevelType $level;

    public function __construct() {
        $this->userType = RoleType::ADMIN;
    }

    // getters and setters
    public function getLevel(): AdminLevelType
    {
        return $this->level;
    }

    public function setLevel(AdminLevelType $level): void
    {
        $this->level = $level;
    }
}
