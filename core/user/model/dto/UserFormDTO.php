<?php

namespace dto;

use Cassandra\Date;

class UserFormDTO
{
    private string $email;
    private string $fullName;
    private string $nickname;
    private string $gender;
    private string $phoneNumber;
    private Date $birthDate;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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