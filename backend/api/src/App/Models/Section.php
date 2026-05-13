<?php

namespace App\Models;

class Section {
    private int $id;
    private string $title;
    private string $description;
    private int $position;
    private Course $course;

    /** @var Lesson[] */
    private array $lessons;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): void
    {
        $this->course = $course;
    }

    public function getLessons(): array
    {
        return $this->lessons;
    }

    public function setLessons(array $lessons): void
    {
        $this->lessons = $lessons;
    }
}