<?php

namespace App\Entity;

use App\Repository\EntryRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass=EntryRepository::class)
 */
class Entry
{
    use UuidAsIdTrait;

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
     * Note, that type of a field should be same as you set in Doctrine config
     * (in this case it is BasketballPositionType)
     *
     * @ORM\Column(type="ItemStatus", nullable=false)
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\ItemStatus")
     */
    private ?string $value = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateTimeCreated = null;

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
