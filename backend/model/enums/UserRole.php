<?php

namespace App\model\enums;

enum UserRole: string {
    case STUDENT = 'student';
    case ADMIN = 'admin';
}
