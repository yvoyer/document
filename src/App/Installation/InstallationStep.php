<?php declare(strict_types=1);

namespace App\Installation;

interface InstallationStep extends InstallationChecker
{
    public function setup(): void;
}
