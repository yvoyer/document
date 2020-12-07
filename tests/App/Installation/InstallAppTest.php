<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Installation;

use Star\Component\Document\Tests\App\AppUris;
use Star\Component\Document\Tests\App\RegressionTestCase;

final class InstallAppTest extends RegressionTestCase
{
    public function test_it_should_setup_db_on_first_touch(): void
    {
        $client = self::createTestClient();
        $client
            ->sendRequest(AppUris::dashboard()->createRequest())
            ->then()
            ->assertRedirectingTo('/setup')
            ->followRedirect() // perform install
            ->assertRedirectingTo('/')
            ->followRedirect() // come back to dashboard
            ->assertFlashMessage('The installation was completed.', 'success');
    }

    public function test_it_should_not_setup_db_when_already_setup(): void
    {
        $client = self::createTestClient();
        $client
            ->sendRequest(AppUris::setup()->createRequest())
            ->then()
            ->assertRedirectingTo('/');

        $client
            ->sendRequest(AppUris::dashboard()->createRequest())
            ->then()
            ->assertCurrentPageIs('http://localhost/', 200);
    }
}
