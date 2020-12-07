<?php declare(strict_types=1);

namespace App\Installation;

interface InstallationChecker
{
    const TAG_NAME = 'app.installation_step';

    /**
     * Whether the installation was completed
     *
     * @return bool
     */
    public function isInstalled(): bool;
}
