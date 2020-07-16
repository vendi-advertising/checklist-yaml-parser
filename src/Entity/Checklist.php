<?php

namespace App\Entity;

use App\Repository\ChecklistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChecklistRepository::class)
 */
class Checklist
{
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="checklists")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $createdBy = null;

    /**
     * @ORM\ManyToOne(targetEntity=ChecklistTemplate::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ChecklistTemplate $template = null;

    /**
     * @ORM\OneToMany(targetEntity=ChecklistItemGroup::class, mappedBy="checklist", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $checklistGroups;

    public function __construct()
    {
        $this->checklistGroups = new ArrayCollection();
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

    public function getTemplate(): ?ChecklistTemplate
    {
        return $this->template;
    }

    public function setTemplate(?ChecklistTemplate $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Collection|ChecklistItemGroup[]
     */
    public function getChecklistGroups(): Collection
    {
        return $this->checklistGroups;
    }

    public function addChecklistGroup(ChecklistItemGroup $checklistGroup): self
    {
        if (!$this->checklistGroups->contains($checklistGroup)) {
            $this->checklistGroups[] = $checklistGroup;
            $checklistGroup->setChecklist($this);
        }

        return $this;
    }

    public function removeChecklistGroup(ChecklistItemGroup $checklistGroup): self
    {
        if ($this->checklistGroups->contains($checklistGroup)) {
            $this->checklistGroups->removeElement($checklistGroup);
            // set the owning side to null (unless already changed)
            if ($checklistGroup->getChecklist() === $this) {
                $checklistGroup->setChecklist(null);
            }
        }

        return $this;
    }
}
