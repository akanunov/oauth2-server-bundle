<?php

namespace OAuth2\ServerBundle\Command;

use OAuth2\ServerBundle\Manager\ScopeManager;
use OAuth2\ServerBundle\User\OAuth2UserProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CreateUserCommand extends Command
{
    private OAuth2UserProvider $userProvider;

    /**
     * @required
     */
    public function setUserProvider(OAuth2UserProvider $userProvider): void
    {
        $this->userProvider = $userProvider;
    }
    protected function configure()
    {
        $this
            ->setName('OAuth2:CreateUser')
            ->setDescription('Create a basic OAuth2 user')
            ->addArgument('username', InputArgument::REQUIRED, 'The users unique username')
            ->addArgument('password', InputArgument::REQUIRED, 'The users password (plaintext)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userProvider = $this->userProvider;

        try {
            $userProvider->createUser($input->getArgument('username'), $input->getArgument('password'));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $output->writeln('<fg=red>Unable to create user ' . $input->getArgument('username') . '</fg=red>');

            return 1;
        }

        $output->writeln('<fg=green>User ' . $input->getArgument('username') . ' created</fg=green>');
        return 0;
    }
}
