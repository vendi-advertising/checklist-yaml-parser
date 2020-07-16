<?php

namespace App\Entity;

use App\Repository\ChecklistItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChecklistItemRepository::class)
 */
class ChecklistItem
{
    use SortOrderTrait;
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity=ChecklistEntry::class, mappedBy="checklistItem", orphanRemoval=true)
     */
    private Collection $checklistEntries;

    /**
     * @ORM\ManyToOne(targetEntity=ChecklistItemGroup::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $checklistItemGroup;

    public function __construct()
    {
        $this->checklistEntries = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ChecklistEntry[]
     */
    public function getChecklistEntries(): Collection
    {
        return $this->checklistEntries;
    }

    public function addChecklistEntry(ChecklistEntry $checklistEntry): self
    {
        if (!$this->checklistEntries->contains($checklistEntry)) {
            $this->checklistEntries[] = $checklistEntry;
            $checklistEntry->setChecklistItem($this);
        }

        return $this;
    }

    public function removeChecklistEntry(ChecklistEntry $checklistEntry): self
    {
        if ($this->checklistEntries->contains($checklistEntry)) {
            $this->checklistEntries->removeElement($checklistEntry);
            // set the owning side to null (unless already changed)
            if ($checklistEntry->getChecklistItem() === $this) {
                $checklistEntry->setChecklistItem(null);
            }
        }

        return $this;
    }

    public function getChecklistItemGroup(): ?ChecklistItemGroup
    {
        return $this->checklistItemGroup;
    }

    public function setChecklistItemGroup(?ChecklistItemGroup $checklistItemGroup): self
    {
        $this->checklistItemGroup = $checklistItemGroup;

        return $this;
    }
}
