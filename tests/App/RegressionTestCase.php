<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class RegressionTestCase extends WebTestCase
{
    static $class = Kernel::class;

    protected static function createTestClient(): TestClient
    {
        $_ENV['APP_ENV'] = 'test';
        $_ENV['APP_DEBUG'] = false;
        $_ENV['DATABASE_URL'] = 'sqlite:///:memory:';

        $client = self::createClient();

        return new TestClient($client, $client->getContainer());
    }
}
