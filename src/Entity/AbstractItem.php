<?php

namespace App\Entity;

abstract class AbstractItem implements ItemInterface
{
    private string $displayText;
    private string $itemType;

    public function __construct(string $displayText, string $itemType)
    {
        $this->displayText = $displayText;
        $this->itemType = $itemType;
    }

    final public function getDisplayText(): string
    {
        return $this->displayText;
    }

    final public function getItemType(): string
    {
        return $this->itemType;
    }
}
