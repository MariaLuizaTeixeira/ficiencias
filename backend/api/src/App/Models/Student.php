<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Models\Enum\GenderType;
use App\Models\Enum\RoleType;
use Cassandra\Date;

#[AllowDynamicProperties]
class Student extends User {
    // attributes
    private int $lives;
    private int $streak;
    private Date $birth;
    private string $nickname;
    private GenderType $gender;
    private string $phoneNumber;
    private string $profilePicture;

    // constructor
    public function __construct() {
        $this->lives = 5;
        $this->streak = 0;
        $this->profilePicture = "https://www.shutterstock.com/image-vector/default-avatar-profile-icon-social-600nw-1906669723.jpg";
        $this->userType = RoleType::STUDENT;
    }

    // getters and setters
    public function getLives(): int
    {
        return $this->lives;
    }

    public function setLives(int $lives): void
    {
        $this->lives = $lives;
    }

    public function getStreak(): int
    {
        return $this->streak;
    }

    public function setStreak(int $streak): void
    {
        $this->streak = $streak;
    }

    public function getBirth(): Date
    {
        return $this->birth;
    }

    public function setBirth(Date $birth): void
    {
        $this->birth = $birth;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getGender(): GenderType
    {
        return $this->gender;
    }

    public function setGender(GenderType $gender): void
    {
        $this->gender = $gender;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }


}