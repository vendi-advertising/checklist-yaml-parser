<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class ItemStatus extends AbstractEnumType
{
    public const NOT_SET = 'nope';
    public const NOT_APPLICABLE = 'na';
    public const CHECKED = 'done';

    protected static $choices = [
        self::NOT_SET => 'Not Set',
        self::NOT_APPLICABLE => 'Not Applicable',
        self::CHECKED => 'Checked',
    ];
}
