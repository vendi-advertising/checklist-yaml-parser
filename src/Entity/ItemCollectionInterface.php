<?php

namespace App\Entity;

interface ItemCollectionInterface extends ItemInterface
{
    /**
     * @return ItemInterface[]
     */
    public function getItems(): array;

    public function addItem(ItemInterface $item, string $key = null);
}
