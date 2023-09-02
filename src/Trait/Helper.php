<?php

namespace App\Trait;


Trait Helper
{
    function snakeToUcWord($input): string
    {
        return ucwords(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }
}