<?php

namespace App\Hashing;

use JsonException;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;

class ObjectHasher
{
    private static ?ObjectHasher $instance = null;

    private array $hashes = [];

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param HashableObjectInterface $obj
     *
     * @param bool                    $forceRefresh
     *
     * @return string
     * @throws JsonException
     * @throws ObjectHashingException
     */
    public function hashObject(HashableObjectInterface $obj, bool $forceRefresh = false): string
    {
        $tmpHash = spl_object_hash($obj);
        if ($forceRefresh || !array_key_exists($tmpHash, $this->hashes)) {
            $buf = $this->buildArray($obj);
            $json = json_encode($buf, JSON_THROW_ON_ERROR);
            $this->hashes[$tmpHash] = hash('sha256', $json);
        }

        return $this->hashes[$tmpHash];
    }

    /**
     * @param HashableObjectInterface $obj
     *
     * @return array
     * @throws ObjectHashingException
     * @throws JsonException
     */
    protected function buildArray(HashableObjectInterface $obj): array
    {
        $pa = (new PropertyAccessorBuilder())->getPropertyAccessor();
        $properties = $obj->getHashProperties();
        $buf = [];
        foreach ($properties as $property) {
            if (!$pa->isReadable($obj, $property)) {
                throw new ObjectHashingException(sprintf('Could not access property %1$s on object %2$s', $property, get_debug_type($obj)));
            }

            $value = $pa->getValue($obj, $property);

            if (null === $value) {
                throw new ObjectHashingException(sprintf('Encountered null value for property %1$s while hashing object %2$s', $property, get_debug_type($obj)));
            }

            if (is_scalar($value)) {
                $buf[$property] = $value;
            } elseif ($value instanceof HashableObjectInterface) {
                $buf[$property] = $this->hashObject($value);
            } elseif ($value instanceof UuidInterface) {
                $buf[$property] = $value->getHex();
            } else {
                throw new ObjectHashingException(sprintf('Unsupported type %3$s encountered for property %1$s while hashing object %2$s', $property, get_debug_type($obj), get_debug_type($value)));
            }
        }
        return $buf;
    }
}
