<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class RegressionTestCase extends WebTestCase
{
    use AssertGroupFunctionalAnnotation;

    static $class = Kernel::class;

    protected static function createTestClient(): TestClient
    {
        $client = self::createClient();

        return new TestClient($client, $client->getContainer());
    }
}
