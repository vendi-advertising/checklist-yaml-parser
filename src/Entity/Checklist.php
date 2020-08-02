<?php

namespace App\Entity;

use App\DBAL\Types\ItemStatus;
use App\DTO\ChecklistStatus;
use App\Entity\Traits\DateTimeCreatedTrait;
use App\Entity\Traits\UuidAsIdTrait;
use App\Hashing\HashableObject;
use App\Repository\ChecklistRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ChecklistRepository::class)
 */
class Checklist extends HashableObject implements JsonSerializable
{
    use UuidAsIdTrait;
    use DateTimeCreatedTrait;

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
     * @ORM\ManyToOne(targetEntity=Template::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Template $template = null;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="checklist", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $sections;

    private ?ChecklistStatus $checklistStatus = null;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->dateTimeCreated = new DateTimeImmutable();
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

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setChecklist($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
            // set the owning side to null (unless already changed)
            if ($section->getChecklist() === $this) {
                $section->setChecklist(null);
            }
        }

        return $this;
    }

    public function hasItem(Item $testItem): bool
    {
        foreach ($this->getSections() as $section) {
            foreach ($section->getItems() as $item) {
                if ($item->getId() === $testItem->getId()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getHashProperties(): array
    {
        return [
            'template',
        ];
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'createBy' => $this->getCreatedBy(),
            'template' => $this->getTemplate(),
            'sections' => $this->getSections()->toArray(),
        ];
    }

    public function calculateChecklistStatus(): ChecklistStatus
    {
        if (!$this->checklistStatus) {
            $this->checklistStatus = new ChecklistStatus();
            foreach ($this->getSections() as $section) {
                foreach ($section->getItems() as $item) {
                    switch ($item->getLastEntryValue()) {
                        case ItemStatus::CHECKED:
                            $this->checklistStatus->incrementCompletedItems();
                            break;
                        case ItemStatus::NOT_APPLICABLE:
                            $this->checklistStatus->incrementNotApplicableItems();
                            break;
                        case ItemStatus::NOT_SET;
                            $this->checklistStatus->incrementIncompleteItems();
                            break;
                    }
                }
            }
        }

        return $this->checklistStatus;
    }
}
