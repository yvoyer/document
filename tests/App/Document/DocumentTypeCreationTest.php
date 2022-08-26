<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Document;

use Star\Component\Document\Tests\App\AppUris;
use Star\Component\Document\Tests\App\RegressionTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
final class DocumentTypeCreationTest extends RegressionTestCase
{
    public function test_it_should_create_document(): void
    {
        $client = self::createTestClient();
        $client->sendRequest(AppUris::dashboard()->createRequest())
            ->userSubmitNewDocument('New document')
            ->then()
            ->assertStatusCode(Response::HTTP_FOUND)
            ->followRedirect()
            ->dumpResponse()
            ->assertCurrentPageIs('/document-types/document-')
            ->dumpResponse()
            ->assertBodyContains('dasdsa')
        ;
    }
}
