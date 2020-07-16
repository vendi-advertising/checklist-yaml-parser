<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class ItemStatus extends AbstractEnumType
{
    public const NOT_SET = 'not-set';
    public const NOT_APPLICABLE = 'na';
    public const CHECKED = 'checked';

    protected static $choices = [
        self::NOT_SET => 'Not Set',
        self::NOT_APPLICABLE => 'Not Applicable',
        self::CHECKED => 'Checked',
    ];
}
