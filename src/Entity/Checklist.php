<?php

namespace App\Entity;

final class Checklist extends GroupedSublistItem
{
    public function getItemType(): string
    {
        return self::ITEM_TYPE_CHECKLIST;
    }
}
