<?php

namespace App\Service;

use App\Entity\Checklist;
use App\Entity\Item;
use App\Entity\Section;
use App\Entity\SpecialYamlName;
use App\Exceptions\InvalidItemTypeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

final class ChecklistParser
{
    protected ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param string $filename
     *
     * @return Checklist
     * @throws InvalidItemTypeException
     */
    public function parseFileFromRoot(string $filename): Checklist
    {
        $data = Yaml::parseFile(Path::join($this->parameterBag->get('kernel.project_dir'), $filename));

//        $checklist_name = array_key_first($data);
        $checklist_items = array_shift($data);

        if (!is_array($checklist_items)) {
            throw new InvalidItemTypeException(sprintf('An item of type %1$s was encountered where an array was expected', gettype($checklist_items)));
        }

        $checklist = new Checklist();
        foreach ($checklist_items as $itemGroupName => $items) {
            $itemGroup = new Section();
            $itemGroupNameParts = SpecialYamlName::parseString($itemGroupName);
            $itemGroup
                ->setName($itemGroupNameParts->getName())
                ->setSortOrder($itemGroupNameParts->getSortOrder());
            foreach ($items as $itemName) {
                $itemNameParts = SpecialYamlName::parseString($itemName);
                $itemGroup->addItem(
                    (new Item())
                        ->setName($itemNameParts->getName())
                        ->setSortOrder($itemNameParts->getSortOrder())
                );
            }
            $checklist->addSection($itemGroup);
        }
//        $checklist->validate();

        return $checklist;
    }
}
