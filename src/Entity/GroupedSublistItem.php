<?php

namespace App\Entity;

final class GroupedSublistItem extends AbstractItemCollection
{
    public function __construct(string $displayText)
    {
        parent::__construct($displayText, self::ITEM_TYPE_GROUP_SUBLIST);
    }
}
