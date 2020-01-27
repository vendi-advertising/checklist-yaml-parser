<?php

namespace App\Entity;

final class Checklist
{
	private string $name;

	/* @var Section[] */
	private array $sections = [];

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return Section[]
	 */
	public function getSections(): array
	{
		return $this->sections;
	}

	public function addSection(Section $section): void
	{
		$this->sections[] = $section;
	}

	public function validate(): void
	{
		$hashes = [];
		foreach ($this->getSections() as $section) {
			foreach ($section->getItems() as $item) {
				if (in_array($item->getHash(), $hashes, true)) {
					throw new \RuntimeException(sprintf('Checklist has duplicate item: %1$s', $item->getName()));
				}
				$hashes[] = $item->getHash();
			}
		}
	}
}
