<?php

namespace App\Entity;

use App\Entity\Traits\UuidAsIdTrait;
use App\Hashing\HashableObject;
use App\Repository\TemplateRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template extends HashableObject implements JsonSerializable
{
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $templateFile = null;

    /**
     * @ORM\OneToMany(targetEntity="Checklist", mappedBy="template")
     */
    private ?Collection $checklists = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTemplateFile(): ?string
    {
        return $this->templateFile;
    }

    public function setTemplateFile(string $templateFile): self
    {
        $this->templateFile = $templateFile;

        return $this;
    }

    public function getHashProperties(): array
    {
        return [
            'id',
        ];
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'templateFile' => $this->getTemplateFile(),
        ];
    }

    public function getChecklists(): ?Collection
    {
        return $this->checklists;
    }
}
