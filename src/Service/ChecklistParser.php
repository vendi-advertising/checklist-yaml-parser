<?php

namespace App\Service;

use App\Entity\Checklist;
use App\Entity\GroupedSublistItem;
use App\Entity\ItemCollectionInterface;
use App\Entity\ItemInterface;
use App\Entity\Section;
use App\Entity\SimpleItem;
use App\Entity\SublistItem;
use App\Exceptions\ArrayMissingRequiredKeyException;
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
     * @throws ArrayMissingRequiredKeyException
     * @throws InvalidItemTypeException
     */
    public function parseFileFromRoot(string $filename): Checklist
    {
        $data = Yaml::parseFile(Path::join($this->parameterBag->get('kernel.project_dir'), $filename));

        $checklist_name = array_key_first($data);
        $checklist_items = array_shift($data);

        if (!is_array($checklist_items)) {
            throw new InvalidItemTypeException(sprintf('An item of type %1$s was encountered where an array was expected', gettype($checklist_items)));
        }

        $checklist = new Checklist($checklist_name);
        $this->walkItem($checklist, $checklist_items);

        return $checklist;
    }

    /**
     * @param ItemCollectionInterface $currentItem
     * @param mixed                   $newValues
     *
     * @throws ArrayMissingRequiredKeyException
     * @throws InvalidItemTypeException
     */
    private function walkItem(ItemCollectionInterface $currentItem, array $newValues): void
    {
        foreach ($newValues as $k => $v) {
            switch ($currentItem->getItemType()) {

                // Sections are really just a special-case for GroupSubList,
                // however because they exist at the root level they are used
                // to break things up more.
                // TODO: See if Checklist can be merged with GroupSubList
                case ItemCollectionInterface::ITEM_TYPE_CHECKLIST:
                    foreach ($newValues as $subK => $subV) {
                        $section = new Section($subK);
                        $this->walkItem($section, $subV);
                        $currentItem->addItem($section);
                    }
                    break;

                case ItemCollectionInterface::ITEM_TYPE_GROUP_SUBLIST:
                    foreach ($v as $subV) {
                        $this->handleCollectionOrStringDuringWalk($currentItem, $subV, $k);
                    }
                    break;

                default:
                    $this->handleCollectionOrStringDuringWalk($currentItem, $v);
                    break;
            }
        }

    }

    /**
     * @param ItemCollectionInterface $currentItem
     * @param                         $v
     * @param string|null             $g
     *
     * @throws InvalidItemTypeException
     * @throws ArrayMissingRequiredKeyException
     */
    private function handleCollectionOrStringDuringWalk(ItemCollectionInterface $currentItem, $v, string $g = null): void
    {
        if (is_scalar($v)) {
            $currentItem->addItem(new SimpleItem($v), $g);
            return;
        }

        if (!is_array($v)) {
            throw new InvalidItemTypeException(sprintf('An item of type %1$s was encountered where an array was expected', gettype($v)));
        }

        $required = ['text', 'items'];
        foreach ($required as $r) {
            if (!array_key_exists($r, $v)) {
                throw new ArrayMissingRequiredKeyException($r);
            }
        }

        $type = $v['type'] ?? ItemInterface::ITEM_TYPE_SUBLIST;
        $text = $v['text'];
        $items = $v['items'];

        /* @var ItemCollectionInterface $c */
        $c = null;

        switch ($type) {
            case ItemInterface::ITEM_TYPE_SUBLIST:
                $c = new SublistItem($text);
                break;

            case ItemInterface::ITEM_TYPE_GROUP_SUBLIST:
                $c = new GroupedSublistItem($text);
                break;

            default:
                throw new InvalidItemTypeException(sprintf('An unknown collection type was encountered: %1$s', $type));
        }

        $this->walkItem($c, $items);
        $currentItem->addItem($c);
    }
}
