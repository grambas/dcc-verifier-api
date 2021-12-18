<?php

declare(strict_types=1);

namespace App\Command;

use Grambas\Client\GermanyTrustListClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateTrustListCommand extends Command
{
    protected static $defaultName = 'dcc:trust-list:update';

    protected $trustListLastCheckFile;
    protected $resourcesDir;

    public function __construct(string $resourcesDir, string $trustListLastCheckFile)
    {
        $this->resourcesDir = $resourcesDir;
        $this->trustListLastCheckFile = $trustListLastCheckFile;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'demo',
            null,
            InputOption::VALUE_OPTIONAL,
            'Trust list environment',
            false
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $demo = $input->getOption('demo');

        $client = new GermanyTrustListClient($this->resourcesDir.'/dsc', $demo);

        $io->success(GermanyTrustListClient::UPDATED === $client->update() ? 'Updated' : 'No Update needed');

        touch($this->trustListLastCheckFile);

        return Command::SUCCESS;
    }
}
