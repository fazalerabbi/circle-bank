<?php

namespace App\Command;

use App\Service\BankService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:do-bank-transactions',
    description: 'Can do three bank transactions
                    1 - Deposit
                    2 - Withdraw
                    3 - Transfer
                    This command required following arguments.
                        1 - This first argument is user_id.
                        2 - Second argument is account_number .
                        3 - Third argument is amount to deposit or withdraw or transfer.
                        4 - Fourth argument is receiver account number. This argument is required only for transfer command.
                        5 - Fifth argument is the transaction. This argument is optional. Default is deposit.
                        6 - Sixth argument is the account type. This argument is optional. In this case enter the amount to 0.
                    ',
)]
class DoBankTransactionsCommand extends Command
{
    public function __construct(private BankService $bankService)
    {
        parent::__construct();
    }

    private const transactionTypes = [
        'DEPOSIT' => 'deposit',
        'WITHDRAW' => 'withdraw',
        'TRANSFER' => 'transfer',
        'SWITCH' => 'switch'
    ];
    protected function configure(): void
    {
        $this
            ->addArgument('user_id', InputArgument::REQUIRED, 'user id is required')
            ->addArgument('account_number', InputArgument::REQUIRED, 'Account number is required')
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount is required')
            ->addOption('receiver_account_number', null, InputOption::VALUE_REQUIRED, 'If transaction is transfer then receiver account number is required')
            ->addOption('transaction_type', null, InputOption::VALUE_REQUIRED, 'Transaction type. Default is deposit',
                self::transactionTypes['DEPOSIT'])
            ->addOption('account_type', null, InputOption::VALUE_REQUIRED, 'Account type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userId = $input->getArgument('user_id');
        $accountNumber = $input->getArgument('account_number');
        $amount = $input->getArgument('amount');
        $transactionType = $input->getOption('transaction_type');
        $receiverAccountNumber = $input->getOption('receiver_account_number');
        $accountType = $input->getOption('account_type');

        if (!in_array($transactionType, self::transactionTypes)) {
            $io->error('Transaction type is not valid');
            return Command::FAILURE;
        }

        if ($transactionType === self::transactionTypes['TRANSFER'] && empty($receiverAccountNumber)) {
            $io->error('Receiver account number is required for transfer transaction');
            return Command::FAILURE;
        }

        if ($transactionType === self::transactionTypes['SWITCH'] && empty($accountType)) {
            $io->error('Account type is required for switch transaction');
            return Command::FAILURE;
        }

            $io->info(sprintf("User id = %s, Account number = %s, Amount = %s, Transaction type = %s, Receiver account number = %s",
            $userId, $accountNumber, $amount, $transactionType, $receiverAccountNumber));

        try {
            if ($transactionType === self::transactionTypes['TRANSFER']) {
                $balance = $this->bankService->transfer($userId, $accountNumber, $amount, $receiverAccountNumber);
                $io->success(sprintf("Balance is %s", $balance));
            } elseif ($transactionType === self::transactionTypes['WITHDRAW']) {
                $balance = $this->bankService->withdraw($userId, $accountNumber, $amount);
                $io->success(sprintf("Balance is %s", $balance));
            } elseif ($transactionType === self::transactionTypes['SWITCH']) {
                $accountType = $this->bankService->switch($userId, $accountNumber, $accountType);
                $io->success(sprintf("Account type switch to %s", $accountType));
            }
            else {
                $balance = $this->bankService->deposit($userId, $accountNumber, $amount);
                $io->success(sprintf("Balance is %s", $balance));
            }

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        //dd($userId, $accountNumber, $amount, $transactionType, $receiverAccountNumber);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
