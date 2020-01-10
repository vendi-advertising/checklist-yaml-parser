<?php

namespace App\Entity;

abstract class AbstractItemCollection extends SimpleItem implements ItemCollectionInterface
{
    protected array $items = [];

    final public function getItems(): array
    {
        return $this->items;
    }

    final public function addItem(ItemInterface $item, string $key = null): void
    {
        if ($key) {
            $this->items[$key][] = $item;
        } else {
            $this->items[] = $item;
        }
    }
}
