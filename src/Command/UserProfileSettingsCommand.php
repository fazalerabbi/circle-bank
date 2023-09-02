<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\UserRepository;

#[AsCommand(
    name: 'app:user-profile-settings',
    description: 'Update the user profile settings. One argument is required that is user_id. Each argument is optional. Here is the list of optional
                        1- user_id: User id who\'s profile settings are updating.
                        2- first_name: To update the first name of the user.
                        3- last_name: To update the first name of the user.
                        4- gender: To update the gender of the user.'
)]
class UserProfileSettingsCommand extends Command
{
    public function __construct(private UserRepository $userRepository)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('user_id', InputArgument::REQUIRED, 'user id is required')
            ->addOption('first_name', null, InputOption::VALUE_REQUIRED, 'First name of the user to update')
            ->addOption('last_name', null, InputOption::VALUE_REQUIRED, 'Last name of the user to update')
            ->addOption('gender', null, InputOption::VALUE_REQUIRED, 'Gender of the user to update')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userId = $input->getArgument('user_id');
        $firstName = $input->getOption('first_name');
        $lastName = $input->getOption('last_name');
        $gender = $input->getOption('gender');

        try {
            if ($firstName) {
                $data = $this->userRepository->updateUser((int) $userId, 'first_name', $firstName);
            }
            if ($lastName) {
                $data = $this->userRepository->updateUser((int) $userId, 'last_name', $lastName);
            }
            if (in_array($gender, ['F', 'M'])) {
                $data = $this->userRepository->updateUser((int) $userId, 'gender', $gender);
            }
            $io->success(sprintf("User profile settings are updated for user_id = %s", $userId));
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
