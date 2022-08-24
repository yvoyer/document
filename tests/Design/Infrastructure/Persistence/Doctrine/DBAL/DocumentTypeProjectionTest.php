<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class DocumentTypeProjectionTest extends RegressionTestCase
{
    public function test_it_should_create_row_on_document_create(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();

        $documentId = $fixtures->newDocumentType(
            'List of cars',
            'en',
            $fixtures->newMember()->getMemberId()
        )
            ->getDocumentTypeId();

        $fixtures->assertDocumentType($documentId)
            ->assertNameIsTranslatedTo('List of cars', 'en')
            ->assertPropertyCount(0);
    }

    public function test_it_should_create_property_on_add_property(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();

        $typeId = $fixtures->newDocumentType(
            'List of planes',
            'en',
            $fixtures->newMember()->getMemberId()
        )
            ->withTextProperty('Plane name', 'en')->endProperty()
            ->getDocumentTypeId();

        $fixtures->assertDocumentType($typeId, 'en')
            ->assertNameIsTranslatedTo('List of planes', 'en')
            ->assertPropertyCount(1)
            ->enterProperty('plane-name')
            ->assertTypeIsText()
            ->assertNameIsTranslatedTo('Plane name', 'en')
            ->assertContainsNoParameters();
    }
}
