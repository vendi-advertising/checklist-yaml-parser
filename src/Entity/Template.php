<?php

namespace App\Entity;

use App\Entity\Traits\UuidAsIdTrait;
use App\Hashing\HashableObject;
use App\Repository\TemplateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template extends HashableObject
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
}
