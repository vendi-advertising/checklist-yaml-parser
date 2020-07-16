<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SortOrderTrait
{
    /**
     * @ORM\Column(type="integer")
     */
    private ?int $sortOrder = null;

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }
}
