<?php

namespace App\Command;

use App\Entity\Checklist;
use App\Entity\GroupedSublistItem;
use App\Entity\ItemCollectionInterface;
use App\Entity\ItemInterface;
use App\Entity\Section;
use App\Entity\SimpleItem;
use App\Entity\SublistItem;
use App\Exceptions\ArrayMissingRequiredKeyException;
use App\Exceptions\InvalidItemTypeException;
use App\Service\ChecklistParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

class ParseCommand extends Command
{
    protected static $defaultName = 'app:parse';

    private ChecklistParser $checklistParser;

    public function __construct(ChecklistParser $checklistParser)
    {
        parent::__construct();
        $this->checklistParser = $checklistParser;
    }

    protected function configure()
    {
        $this->setDescription('Parse the sample YAML file');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ArrayMissingRequiredKeyException
     * @throws InvalidItemTypeException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $checklist = $this->checklistParser->parseFileFromRoot('sample.yaml');

        dump($checklist);

        return 0;
    }

}
