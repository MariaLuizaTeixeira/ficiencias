<?php

namespace App\Models;

use App\Enum\AdminLevelType;

class Admin extends User {
    // attributes
    private AdminLevelType $level;

    public function getLevel(): AdminLevelType
    {
        return $this->level;
    }

    public function setLevel(AdminLevelType $level): void
    {
        $this->level = $level;
    }
}
