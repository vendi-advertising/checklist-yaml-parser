<?php

namespace App\Entity;

class SimpleItem implements ItemInterface
{
    public string $displayText;

    public function __construct(string $displayText)
    {
        $this->displayText = $displayText;
    }

    final public function getDisplayText(): string
    {
        return $this->displayText;
    }

    public function getItemType(): string
    {
        return self::ITEM_TYPE_SIMPLE;
    }
}
