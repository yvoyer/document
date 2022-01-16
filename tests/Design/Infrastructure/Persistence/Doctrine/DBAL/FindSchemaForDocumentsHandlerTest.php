<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocuments;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class FindSchemaForDocumentsHandlerTest extends RegressionTestCase
{
    public function test_it_should_fetch_all_schema_with_ids(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $doc_one = $fixtures
            ->newDocument($memberId)
            ->getDocumentId();
        $doc_two = $fixtures
            ->newDocument($memberId)
            ->getDocumentId();
        $doc_three = $fixtures
            ->newDocument($memberId)
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindSchemaForDocuments($doc_one, $doc_three));
        self::assertCount(2, $result = $query->getFoundSchemas());
        self::assertContainsOnlyInstancesOf(ReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame(['text'], $row->getPublicProperties());
        self::assertSame(
            StringType::fromData([])->toData()->toString(),
            $row->getPublicProperty('text')->toString()
        );
        \var_dump($row);
        self::assertSame(
            StringType::fromData([])->toData()->toString(),
            $row->getPublicProperty('text')->toString()
        );
    }

    public function test_it_should_fetch_documents_with_properties(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $fixtures
            ->newDocument($memberId)
            ->withTextProperty('text')->endProperty()
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindSchemaForDocuments($memberId));
        self::assertCount(1, $result = $query->getResultArray());
        self::assertContainsOnlyInstancesOf(ReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame(['text'], $row->getPublicProperties());
        self::assertSame(
            StringType::fromData([])->toData()->toString(),
            $row->getPublicProperty('text')->toString()
        );
        \var_dump($row);
        self::assertSame(
            StringType::fromData([])->toData()->toString(),
            $row->getPublicProperty('text')->toString()
        );
    }

    public function test_it_should_fetch_documents_with_properties_having_constraint(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $fixtures
            ->newDocument($memberId)
            ->withTextProperty('text')->required()->endProperty()
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindSchemaForDocuments($memberId));
        self::assertCount(1, $result = $query->getResultArray());
        self::assertContainsOnlyInstancesOf(ReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame(['text'], $row->getPublicProperties());
        self::assertSame(StringType::fromData([])->toData()->toString(), $row->getPublicProperty('text')->toString());
        $this->fail('todo');
    }
}
