<?php

namespace App\Entity;

class SublistItem extends AbstractItemCollection
{
    public function getItemType(): string
    {
        return self::ITEM_TYPE_SUBLIST;
    }
}
