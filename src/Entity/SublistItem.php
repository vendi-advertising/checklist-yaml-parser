<?php

namespace App\Entity;

final class SublistItem extends AbstractItemCollection
{
    public function __construct(string $displayText)
    {
        parent::__construct($displayText, self::ITEM_TYPE_SUBLIST);
    }
}
