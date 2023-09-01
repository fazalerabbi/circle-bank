<?php

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-user-info',
    description: 'This command allows you to get the user info. It required two arguments.
                                1 - The first argument is the user_id 
                                2 - The second argument is the key.',
)]
class GetUserInfoCommand extends Command
{
    public function __construct(private UserService $userService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('user_id', InputArgument::REQUIRED, 'User ID to get info');
        $this->addArgument('key', InputArgument::REQUIRED, 'Key ');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userId = $input->getArgument('user_id');
        $key    = $input->getArgument('key');

        try {
            $data = $this->userService->getUserInfo((int) $userId, $key);
            $io->success(sprintf("Data for user_id = %s and key = %s is %s", $userId, $key, $data));
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
