<?php

namespace App\Hashing;

interface HashableObjectInterface
{
    public function getHashProperties(): array;

    public function getHash(): string;
}
