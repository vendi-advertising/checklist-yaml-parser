<?php

/** @noinspection PhpUnused */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
	private string $email;

	/**
	 * @ORM\Column(type="json")
	 */
	private array $roles = [];

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private string $password;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\ChecklistSession", mappedBy="createdBy")
	 */
	private Collection $checklistSessions;

	public function __construct()
	{
		$this->checklistSessions = new ArrayCollection();
	}

	/**
	 * @inheritDoc
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
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
		// TODO: Implement getSalt() method.
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
		// TODO: Implement eraseCredentials() method.
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	/**
	 * @return Collection|ChecklistSession[]
	 */
	public function getChecklistSessions(): Collection
	{
		return $this->checklistSessions;
	}

	public function addChecklistSession(ChecklistSession $checklistSession): self
	{
		if (!$this->checklistSessions->contains($checklistSession)) {
			$this->checklistSessions[] = $checklistSession;
			$checklistSession->setCreatedBy($this);
		}

		return $this;
	}

	public function removeChecklistSession(ChecklistSession $checklistSession): self
	{
		if ($this->checklistSessions->contains($checklistSession)) {
			$this->checklistSessions->removeElement($checklistSession);
			// set the owning side to null (unless already changed)
			if ($checklistSession->getCreatedBy() === $this) {
				$checklistSession->setCreatedBy(null);
			}
		}

		return $this;
	}
}
