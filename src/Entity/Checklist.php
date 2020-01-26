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
}
