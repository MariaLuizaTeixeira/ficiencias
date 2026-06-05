<?php

namespace model\questions;

use Model\enums\ColumnType;
use Model\enums\ContentType;

class AssociativeItem {
    private int $id;
    private string $content;
    private ContentType $contentType;
    private ColumnType $columnType;
    private AssociativeQuestion $associativeQuestion;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    public function setContentType(ContentType $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getColumnType(): ColumnType
    {
        return $this->columnType;
    }

    public function setColumnType(ColumnType $columnType): void
    {
        $this->columnType = $columnType;
    }

    public function getAssociativeQuestion(): AssociativeQuestion
    {
        return $this->associativeQuestion;
    }

    public function setAssociativeQuestion(AssociativeQuestion $associativeQuestion): void
    {
        $this->associativeQuestion = $associativeQuestion;
    }
}