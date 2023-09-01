<?php

namespace App;

use App\Command\GetUserInfoCommand;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureCommands(Command $command): void
    {
        $command->addCommands([
            new GetUserInfoCommand()
        ]);
    }

}
