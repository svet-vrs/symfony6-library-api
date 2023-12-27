<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

#[AsCommand(
    name: 'ChangeUserRoleCommand',
    description: 'Add a short description for your command',
)]
class ChangeUserRoleCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:user:change-role')
            ->setDescription('Change user role');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $userRepository = $this->entityManager->getRepository(User::class);
        $userId = $io->ask('Enter the user ID:');

        $user = $userRepository->find($userId);

        if (!$user) {
            $io->error('User not found!');
            return Command::FAILURE;
        }

        $availableRoles = ['ROLE_CLIENT', 'ROLE_ADMIN']; // Замените на ваши роли
        $question = new ChoiceQuestion(
            'Select a new role:',
            $availableRoles
        );

        $newRole = $io->askQuestion($question);

        $user->setRoles([$newRole]);
        $this->entityManager->flush();

        $io->success('User role updated successfully!');

        return Command::SUCCESS;
    }
}
