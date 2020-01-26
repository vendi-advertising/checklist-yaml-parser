<?php

namespace App\Entity;

final class ChecklistItem
{
	private string $name;

	private string $hash;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getHash(): string
	{
		if (!isset($this->hash)) {
			$this->hash = hash('sha256', $this->getName());
		}
		return $this->hash;
	}
}
