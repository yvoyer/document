<?php declare(strict_types=1);

namespace App\Tests\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class DocumentSchemaManagerTest extends RegressionTestCase
{
    public function test_it_should_create_row_on_document_create(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();

        $documentId = $fixtures->newDocumentType($fixtures->newMember()->getMemberId())
            ->getDocumentTypeId();

        $fixtures->assertDocument($documentId, 'en')
            ->assertName('default-name')
            ->assertPropertyCount(0);
    }

    public function test_it_should_create_property_on_add_property(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();

        $documentId = $fixtures->newDocumentType($fixtures->newMember()->getMemberId())
            ->withTextProperty('text', 'en')->endProperty()
            ->getDocumentTypeId();

        $fixtures->assertDocument($documentId, 'en')
            ->assertName('default-name')
            ->assertPropertyCount(1)
            ->enterPropertyWithName('text')
            ->assertTypeIsText()
            ->assertContainsNoOptions();
        $this->fail('todo');
    }
}
