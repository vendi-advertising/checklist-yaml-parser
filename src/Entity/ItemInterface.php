<?php

namespace App\Entity;

Interface ItemInterface
{
    public const ITEM_TYPE_SIMPLE = 'simple';
    public const ITEM_TYPE_CHECKLIST = 'checklist';
    public const ITEM_TYPE_GROUP_SUBLIST = 'grouped-sublist';
    public const ITEM_TYPE_SECTION = 'section';
    public const ITEM_TYPE_SUBLIST = 'sublist';

    public function getItemType(): string;

    public function getDisplayText(): string;
}
