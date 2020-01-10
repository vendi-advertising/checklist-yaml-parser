<?php

namespace App\Entity;

final class Checklist extends AbstractItemCollection
{
    public function __construct(string $displayText)
    {
        parent::__construct($displayText, self::ITEM_TYPE_CHECKLIST);
    }
}
