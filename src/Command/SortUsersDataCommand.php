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
    name: 'app:get-sorted-data',
    description: "Sorting the user data on provided key: Default: Ascending order
                                1 - This first argument is the key on which the data will be sorted
                                2 - The second argument is the optional to sort the data in desc order add --desc option
                                key can be one of following:
                                a: 'user_id',
                                b: 'first_name',
                                c: 'last_name',
                                d: 'gender',
                                e: 'account_id',
                                f: 'account_type',
                                g: 'account_balance',
                                h: 'account_number'",
)]
class SortUsersDataCommand extends Command
{
    public function __construct(private UserService $userService)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('key', InputArgument::REQUIRED, 'Input the key on which trying to sort the data')
            ->addOption('desc', null, InputOption::VALUE_NONE, 'Add this option if you want to wort desc')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $key = $input->getArgument('key');
        $desc = $input->getOption('desc');

        $sortAsc = !$desc;

        try {
            $data = $this->userService->sortUsersData($key, $sortAsc);

            $io->success(sprintf("Data is sorted on '%s' in %s", $key, $desc ? 'desc' : 'asc'));
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
