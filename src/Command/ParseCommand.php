<?php

namespace App\Command;

use App\Exceptions\ArrayMissingRequiredKeyException;
use App\Exceptions\InvalidItemTypeException;
use App\Service\ChecklistParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
