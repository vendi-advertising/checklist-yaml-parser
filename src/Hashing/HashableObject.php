<?php

namespace App\Hashing;

use JsonException;

abstract class HashableObject implements HashableObjectInterface
{
    /**
     * @return string
     * @throws ObjectHashingException
     * @throws JsonException
     */
    final public function getHash(): string
    {
        return (new ObjectHasher())->hashObject($this);
    }
}
