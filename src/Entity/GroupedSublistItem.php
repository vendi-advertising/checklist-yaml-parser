<?php

namespace App\Entity;

class GroupedSublistItem extends AbstractItemCollection
{
    public function getItemType(): string
    {
        return self::ITEM_TYPE_GROUP_SUBLIST;
    }
}
