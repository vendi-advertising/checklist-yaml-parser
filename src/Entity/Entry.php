<?php

namespace App\Entity;

use App\Entity\Traits\DateTimeCreatedTrait;
use App\Entity\Traits\UuidAsIdTrait;
use App\Repository\EntryRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass=EntryRepository::class)
 */
class Entry
{
    use UuidAsIdTrait;
    use DateTimeCreatedTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="checklistEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Item $checklistItem = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="ItemStatus", nullable=false)
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\ItemStatus")
     */
    private ?string $value = null;

    public function __construct()
    {
        $this->dateTimeCreated = new DateTimeImmutable();
    }

    public function getChecklistItem(): ?Item
    {
        return $this->checklistItem;
    }

    public function setChecklistItem(?Item $checklistItem): self
    {
        $this->checklistItem = $checklistItem;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
