<?php

namespace OAuth2\ServerBundle\Command;

use OAuth2\ServerBundle\Manager\ClientManager;
use OAuth2\ServerBundle\Manager\ScopeManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CreateScopeCommand extends Command
{
    private ScopeManager $scopeManager;

    /**
     * @required
     */
    public function setScopeManager(ScopeManager $scopeManager): void
    {
        $this->scopeManager = $scopeManager;
    }

    protected function configure()
    {
        $this
            ->setName('OAuth2:CreateScope')
            ->setDescription('Create a scope for use in OAuth2')
            ->addArgument('scope', InputArgument::REQUIRED, 'The scope key/name')
            ->addArgument('description', InputArgument::REQUIRED, 'The scope description used on authorization screen')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scopeManager = $this->scopeManager;

        try {
            $scopeManager->createScope($input->getArgument('scope'), $input->getArgument('description'));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $output->writeln('<fg=red>Unable to create scope ' . $input->getArgument('scope') . '</fg=red>');

            return 1;
        }

        $output->writeln('<fg=green>Scope ' . $input->getArgument('scope') . ' created</fg=green>');
        return 0;
    }
}
