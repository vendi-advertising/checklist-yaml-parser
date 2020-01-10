<?php

namespace App\Entity;

final class SimpleItem extends AbstractItem
{
    public function __construct(string $displayText)
    {
        parent::__construct($displayText, self::ITEM_TYPE_SIMPLE);
    }
}
