<?php declare(strict_types=1);

namespace App\Installation;

use function file_exists;
use function file_put_contents;

final class CreateDbWhenNotExists implements InstallationStep
{
    /**
     * @var string
     */
    private $installDir;

    /**
     * @var string
     */
    private $dbFile;

    /**
     * @var string
     */
    private $fullPath;

    public function __construct(string $installDir, string $dbFile)
    {
        $this->installDir = $installDir;
        $this->dbFile = $dbFile;
        $this->fullPath = $this->installDir . '/' . $this->dbFile;
    }

    public function isInstalled(): bool
    {
        return file_exists($this->fullPath);
    }

    public function setup(): void
    {
        file_put_contents($this->fullPath, 'bla');
    }
}
