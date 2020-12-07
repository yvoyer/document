<?php declare(strict_types=1);

namespace App\Installation;

use LogicException;

final class CheckInstallationSteps implements InstallationChecker
{
    /**
     * @var InstallationStep[]
     */
    private $steps = [];

    public function registerStep(InstallationStep $step): void
    {
        $this->steps[] = $step;
    }

    public function isInstalled(): bool
    {
        if (\count($this->steps) === 0) {
            throw new LogicException('No installation steps configured.');
        }

        foreach ($this->steps as $step) {
            if (! $step->isInstalled()) {
                return false;
            }
        }

        return true;
    }
}
