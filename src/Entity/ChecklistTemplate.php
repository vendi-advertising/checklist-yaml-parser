<?php

namespace App\Entity;

use App\Repository\ChecklistTemplateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChecklistTemplateRepository::class)
 */
class ChecklistTemplate
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
}
