<?php

namespace Model;

class MatchingAnswer extends Answer {
    private AssociativeItem $leftItem;
    private AssociativeItem $rightItem;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLeftItem(): AssociativeItem
    {
        return $this->leftItem;
    }

    public function setLeftItem(AssociativeItem $leftItem): void
    {
        $this->leftItem = $leftItem;
    }

    public function getRightItem(): AssociativeItem
    {
        return $this->rightItem;
    }

    public function setRightItem(AssociativeItem $rightItem): void
    {
        $this->rightItem = $rightItem;
    }
}