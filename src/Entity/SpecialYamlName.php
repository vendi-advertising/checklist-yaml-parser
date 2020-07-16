<?php

namespace App\Entity;

final class SpecialYamlName
{
    private ?int $sortOrder;
    private ?string $name;

    public function getSortOrder(): int
    {
        assert(isset($this->sortOrder));
        return $this->sortOrder;
    }

    public function getName(): string
    {
        assert(isset($this->name));
        return $this->name;
    }

    public static function parseString(string $value): self
    {
        $parts = explode(';', $value);
        if (1 === count($parts)) {
            return (new self)->setName($value)->setSortOrder(9999);
        }

        $sortOrder = filter_var(array_shift($parts), FILTER_SANITIZE_NUMBER_INT);
        if (false === $sortOrder) {
            throw new \RuntimeException('Could not convert template name part to int');
        }

        $name = trim(implode(';', $parts));
        return (new self)->setName($name)->setSortOrder($sortOrder);
    }

    private function setSortOrder(?int $sortOrder): SpecialYamlName
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    private function setName(?string $name): SpecialYamlName
    {
        $this->name = $name;
        return $this;
    }
}
