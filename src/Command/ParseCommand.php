<?php

namespace App\Command;

use App\Entity\Section;
use App\Entity\SimpleItem;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

class ParseCommand extends Command
{
    protected static $defaultName = 'app:parse';

    protected $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();
        $this->parameterBag = $parameterBag;
    }

    protected function configure()
    {
        $this->setDescription('Parse the sample YAML file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = Yaml::parseFile(Path::join($this->parameterBag->get('kernel.project_dir'), 'sample.yaml'));

        $checklist_name = array_key_first($data);
        $checklist_items = array_shift($data);

        $ret = [];
        foreach ($checklist_items as $name => $values) {
            $section = new Section($name);
            dump($section);
            $this->walkItem($section, $values);
        }

//        dump($data);
        return 0;
    }

    /**
     * @param SimpleItem $item
     * @param            $newValues
     *
     * @throws Exception
     */
    private function walkItem(SimpleItem $item, $newValues): void
    {
        if (is_array($newValues)) {
            if ($item instanceof Section) {
                $this->walkItemSection($item, $newValues);
            } else {
                throw new RuntimeException('Unknown item type for array walk: ' . get_class($item));
            }
        }

        if(is_string($newValues)){

        }

        foreach ($newValues as $k => $v) {
            if (is_string($v)) {

            }
        }
        dump($values);
    }

    private function walkItemSection(Section $item, array $newValues)
    {
        foreach($newValues as $k => $v){

        }
    }
}
