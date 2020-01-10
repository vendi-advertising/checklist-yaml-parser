<?php

namespace App\Entity;

final class Section extends SublistItem
{
    public function getItemType(): string
    {
        return self::ITEM_TYPE_SECTION;
    }
}
