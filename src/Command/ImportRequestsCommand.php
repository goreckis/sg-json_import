<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Inspection;
use App\Model\ServiceRequest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'import:requests',
    description: 'Imports service request list from the json file',
)]
#[Autoconfigure(bind: ['$projectDir' => '%kernel.project_dir%'])]
class ImportRequestsCommand extends Command
{
    private const IMPORT_DIR = '/public/import/';
    private string $projectDir;

    public function __construct(
        string $projectDir,
        private readonly SerializerInterface $serializer,
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
            $task_object = $this->serializer->deserialize(json_encode($task), ServiceRequest::class, 'json');

            // @todo parse the object and save.
        }

        return Command::SUCCESS;
    }
}
