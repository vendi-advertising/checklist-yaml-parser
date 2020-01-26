<?php

namespace App\Entity;

final class Section
{
	private string $name;

	/* @var ChecklistItem[] */
	private array $items = [];

	public function __construct(string $name, array $items = null)
	{
		$this->name = $name;
		if ($items) {
			$this->items = $items;
		}
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return ChecklistItem[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	public function addItem(ChecklistItem $item): void
	{
		$this->items[] = $item;
	}
}
