<?php

namespace App\Entity;

class SimpleItem
{
    public string $displayText;

    public function __construct(string $displayText)
    {
        $this->displayText = $displayText;
    }

}
