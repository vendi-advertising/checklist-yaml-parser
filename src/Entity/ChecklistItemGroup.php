<?php

namespace App\Entity;

use App\Repository\ChecklistItemGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChecklistItemGroupRepository::class)
 */
class ChecklistItemGroup
{
    use SortOrderTrait;
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\ManyToOne(targetEntity=Checklist::class, inversedBy="checklistGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Checklist $checklist = null;

    /**
     * @ORM\OneToMany(targetEntity=ChecklistItem::class, mappedBy="checklistItemGroup", orphanRemoval=true,
     *                                                   cascade={"persist"})
     */
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getChecklist(): ?Checklist
    {
        return $this->checklist;
    }

    public function setChecklist(?Checklist $checklist): self
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * @return Collection|ChecklistItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ChecklistItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setChecklistItemGroup($this);
        }

        return $this;
    }

    public function removeItem(ChecklistItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getChecklistItemGroup() === $this) {
                $item->setChecklistItemGroup(null);
            }
        }

        return $this;
    }
}
