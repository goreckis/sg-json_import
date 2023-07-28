<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[AsCommand(
    name: 'import:requests',
    description: 'Imports service request list from the json file',
)]
#[Autoconfigure(bind: ['$projectDir' => '%kernel.project_dir%', '$defaultEnv' => '%kernel.environment%'])]
class ImportRequestsCommand extends Command
{
    private const IMPORT_DIR = '/public/import/';
    private string $projectDir;

    public function __construct(
        string $projectDir,
    )
    {
        $this->projectDir = $projectDir;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Name of the json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file_name = $this->projectDir . self::IMPORT_DIR . $input->getArgument('filename');

        if (!$tasks = file_get_contents($file_name)) {
            return Command::FAILURE;
        }
        $tasks = json_decode($tasks);

        foreach ($tasks as $task) {
            $output->writeln([
                'Task number: ' . $task->number . ' imported',
                $task->description,
            ]);
        }

        return Command::SUCCESS;
    }
}
