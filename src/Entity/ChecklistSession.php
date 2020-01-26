<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChecklistSessionRepository")
 */
class ChecklistSession
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $sourceChecklist;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="checklistSessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceChecklist(): ?string
    {
        return $this->sourceChecklist;
    }

    public function setSourceChecklist(string $sourceChecklist): self
    {
        $this->sourceChecklist = $sourceChecklist;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
