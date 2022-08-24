<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\SchemaOfDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocumentTypes;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class FindSchemaForDocumentTypesHandlerTest extends RegressionTestCase
{
    public function test_it_should_fetch_all_schema_with_ids(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $docOneId = $fixtures
            ->newDocumentType('type-1', 'en', $memberId)
            ->getDocumentTypeId();
        $fixtures
            ->newDocumentType('type-2', 'en', $memberId)
            ->getDocumentTypeId();
        $docThreeId = $fixtures
            ->newDocumentType('type-3', 'en', $memberId)
            ->getDocumentTypeId();

        $fixtures->dispatchQuery($query = new FindSchemaForDocumentTypes('en', $docOneId, $docThreeId));
        self::assertCount(2, $result = $query->getAllFoundSchemas());
        self::assertContainsOnlyInstancesOf(SchemaOfDocument::class, $result);
        self::assertSame($docOneId->toString(), $query->getSingleSchema($docOneId)->getDocumentId());
        self::assertSame($docThreeId->toString(), $query->getSingleSchema($docThreeId)->getDocumentId());
    }

    public function test_it_should_fetch_documents_with_properties(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $documentId = $fixtures
            ->newDocumentType('type', 'en', $memberId)
            ->withTextProperty('text', 'en')->endProperty()
            ->getDocumentTypeId();

        $fixtures->dispatchQuery($query = new FindSchemaForDocumentTypes('en', $documentId));
        self::assertCount(1, $result = $query->getAllFoundSchemas());
        self::assertContainsOnlyInstancesOf(SchemaOfDocument::class, $result);

        $document = $query->getSingleSchema($documentId);
        self::assertSame(['text'], $document->getPublicProperties());
        self::assertSame(
            'todo',
            $document->getPublicProperty('text')->toTypedString()
        );
    }

    public function test_it_should_fetch_documents_with_properties_having_constraint(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $documentId = $fixtures
            ->newDocumentType('type', 'en', $memberId)
            ->withTextProperty('text', 'en')->required()->endProperty()
            ->getDocumentTypeId();

        $fixtures->dispatchQuery($query = new FindSchemaForDocumentTypes('en', $documentId));
        self::assertCount(1, $result = $query->getAllFoundSchemas());
        self::assertContainsOnlyInstancesOf(SchemaOfDocument::class, $result);

        $document = $query->getSingleSchema($documentId);
        self::assertSame(['text'], $document->getPublicProperties());
        self::assertSame(
            StringType::fromData([])->toData()->toString(),
            $document->getPublicProperty('text')
        );
        $this->fail('todo');
    }
}
