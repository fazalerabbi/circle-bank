<?php

namespace App;

use App\Command\DoBankTransactionsCommand;
use App\Command\GetUserInfoCommand;
use App\Command\SortUsersDataCommand;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureCommands(Command $command): void
    {
        $command->addCommands([
            new GetUserInfoCommand(),
            new SortUsersDataCommand(),
            new DoBankTransactionsCommand()
        ]);
    }

}
