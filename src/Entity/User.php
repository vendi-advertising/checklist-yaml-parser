<?php

namespace App\Entity;

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
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $email;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

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
	private $password;

	/**
	 * @inheritDoc
	 */
	public function getRoles()
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	/**
	 * @inheritDoc
	 */
	public function getPassword()
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
	public function getUsername()
	{
		return $this->getEmail();
	}

	/**
	 * @inheritDoc
	 */
	public function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}

	public function setPassword(string $password)
	{
		$this->password = $password;
	}
}
