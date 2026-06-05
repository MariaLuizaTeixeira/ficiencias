<?php

namespace model\divisions;

class Course {
    private int $id;
    private string $name;
    private string $description;

    /** @var Section[] */
    private array $sections;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function setSections(array $sections): void
    {
        $this->sections = $sections;
    }
}