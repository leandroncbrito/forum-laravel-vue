<?php

namespace App\Inspections;

use App\Inspections\ISpam;

class KeyHeldDown implements ISpam
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}
