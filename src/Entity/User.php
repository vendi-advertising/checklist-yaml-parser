<?php

namespace App\Entity;

use App\Entity\Traits\DateTimeCreatedTrait;
use App\Entity\Traits\UuidAsIdTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, JsonSerializable
{
    use UuidAsIdTrait;
    use DateTimeCreatedTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $password = null;

    /**
     * @ORM\OneToMany(targetEntity="Checklist", mappedBy="createdBy")
     */
    private ?Collection $checklists = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $displayName;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function __construct(string $email, string $displayName)
    {
        $this->checklists = new ArrayCollection();
        $this->dateTimeCreated = new DateTimeImmutable();
        $this->displayName = $displayName;
        $this->email = $email;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // This is not used by the current security system
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // Passwords are only stored hashed so this method isn't needed
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return Collection|Checklist[]
     */
    public function getChecklists(): Collection
    {
        return $this->checklists;
    }

    public function addChecklist(Checklist $checklist): self
    {
        if (!$this->checklists->contains($checklist)) {
            $this->checklists[] = $checklist;
            $checklist->setCreatedBy($this);
        }

        return $this;
    }

    public function removeChecklist(Checklist $checklist): self
    {
        if ($this->checklists->contains($checklist)) {
            $this->checklists->removeElement($checklist);
            // set the owning side to null (unless already changed)
            if ($checklist->getCreatedBy() === $this) {
                $checklist->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
        ];
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }
}
