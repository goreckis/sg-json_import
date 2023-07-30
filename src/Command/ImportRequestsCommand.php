<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\InvalidRequest;
use App\Model\ServiceRequest;
use App\Service\RequestTypeParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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
        protected RequestTypeParser $typeParser,
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
        $report['failed'] = 0;
        $file_name = $this->projectDir . self::IMPORT_DIR . $input->getArgument('filename');

        if (!$tasks = file_get_contents($file_name)) {
            return Command::FAILURE;
        }
        $tasks = json_decode($tasks);

        foreach ($tasks as $task) {
            $taskObject = $this->serializer->deserialize(json_encode($task), ServiceRequest::class, 'json');
            $entity = $this->typeParser->processRequest($taskObject);

            try
            {
                $entity->setByServiceRequest($taskObject);
                $entity->save();
                $report[$entity::TYPE] = isset($report[$entity::TYPE]) ? $report[$entity::TYPE]+1 : 1;
                $output->writeln([
                    'Created new ' . $entity::TYPE . ' number: ' . $task->number,
                ]);
            }
            catch (\Exception $e)
            {
                $report['failed'] = $report['failed']+1;
                $invalidRequest = new InvalidRequest();
                $invalidRequest->setByServiceRequest($taskObject);
                $invalidRequest->save();
                $output->writeln([
                    'Error during saving task number: ' . $task->number . ': ' . $e->getMessage(),
                ]);
            }
        }

        foreach ($report as $type => $counter) {
            $rows[] = [$type, $counter];
        }
        $table = new Table($output);
        $table->setHeaders(['Type', 'Processed'])->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
