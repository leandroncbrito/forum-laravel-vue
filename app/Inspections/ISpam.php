<?php

namespace App\Inspections;

interface ISpam
{
    public function detect($body);
}
