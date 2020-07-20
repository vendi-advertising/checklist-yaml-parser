<?php

namespace App\Entity;

use App\Entity\Traits\DateTimeCreatedTrait;
use App\Entity\Traits\UuidAsIdTrait;
use App\Repository\NoteRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    use UuidAsIdTrait;
    use DateTimeCreatedTrait;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private ?string $text = null;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Item $item = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function __construct()
    {
        $this->dateTimeCreated = new DateTimeImmutable();
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
}
