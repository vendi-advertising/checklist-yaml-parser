<?php

namespace App\Entity;

use App\Hashing\HashableObject;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item extends HashableObject
{
    use SortOrderTrait;
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity=Entry::class, mappedBy="checklistItem", orphanRemoval=true, fetch="EAGER")
     */
    private Collection $entries;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Section $section = null;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
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

    public function getLastEntryValue(): string
    {
        static $lastEntryValue = null;

        // TODO: This whole function could be optimized
        $entries = $this->getEntries();
        if (!$entries->count()) {
            return \App\DBAL\Types\ItemStatus::NOT_SET;
        }

        $entries = $entries->toArray();

        usort(
            $entries,
            static function (Entry $a, Entry $b) {
                return $a->getDateTimeCreated() <=> $b->getDateTimeCreated();
            }
        );

        $mostRecent = end($entries);

        $lastEntryValue = $mostRecent->getValue();

        return $lastEntryValue;
    }

    /**
     * @return Collection|Entry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setChecklistItem($this);
        }

        return $this;
    }

    public function removeChecklistEntry(Entry $checklistEntry): self
    {
        if ($this->entries->contains($checklistEntry)) {
            $this->entries->removeElement($checklistEntry);
            // set the owning side to null (unless already changed)
            if ($checklistEntry->getChecklistItem() === $this) {
                $checklistEntry->setChecklistItem(null);
            }
        }

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getHashProperties(): array
    {
        return [
            'name',
        ];
    }
}
