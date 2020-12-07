<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use App\Kernel;
use org\bovigo\vfs\vfsStream;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class RegressionTestCase extends WebTestCase
{
    protected static function createKernel(array $options = []): Kernel
    {
        return new Kernel('test', false);
    }

    protected static function createTestClient(): TestClient
    {
        $root = vfsStream::setup('root');
        $_ENV['APP_ENV'] = 'test';
        $_ENV['APP_INSTALL_DIR'] = $root->url();

        return new TestClient(self::createClient());
    }
}
