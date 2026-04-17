<?php

namespace dto;
use Cassandra\Date;

class UserDTO
{
    private int $id;
    private int $lives;
    private string $email;
    private int $streak;
    private string $fullName;
    private string $nickname;
    private string $gender;
    private string $phoneNumber;
    private Date $birthDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLives(): int
    {
        return $this->lives;
    }

    public function setLives(int $lives): void
    {
        $this->lives = $lives;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getStreak(): int
    {
        return $this->streak;
    }

    public function setStreak(int $streak): void
    {
        $this->streak = $streak;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
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

    public function getBirthDate(): Date
    {
        return $this->birthDate;
    }

    public function setBirthDate(Date $birthDate): void
    {
        $this->birthDate = $birthDate;
    }
}