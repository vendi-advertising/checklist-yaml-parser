<?php

namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait DateTimeCreatedTrait
{
    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateTimeCreated = null;

    public function getDateTimeCreated(): ?DateTimeInterface
    {
        return $this->dateTimeCreated;
    }

    public function setDateTimeCreated(DateTimeInterface $dateTimeCreated): self
    {
        $this->dateTimeCreated = $dateTimeCreated;

        return $this;
    }
}
