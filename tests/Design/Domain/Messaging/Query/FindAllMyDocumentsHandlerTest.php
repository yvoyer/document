<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\MyReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Tests\App\RegressionTestCase;

final class FindAllMyDocumentsHandlerTest extends RegressionTestCase
{
    public function test_it_should_fetch_documents_without_properties(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();

        $document = $fixtures
            ->newDocument()
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindAllMyDocuments());
        self::assertCount(1, $result = $query->getResult());
        self::assertContainsOnlyInstancesOf(MyReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame($document->toString(), $row->getDocumentId());
        self::assertSame(['id'], $row->getPublicProperties());
        self::assertSame((new NullType())->toData()->toString(), $row->getPublicProperty('id')->toString());
        self::assertSame((new NullType())->toData()->toString(), $row->getPublicProperty('name')->toString());
    }

    public function test_it_should_fetch_documents_with_properties(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $fixtures
            ->newDocument()
            ->withTextProperty('name')->endProperty()
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindAllMyDocuments());
        self::assertCount(1, $result = $query->getResult());
        self::assertContainsOnlyInstancesOf(MyReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame(['id', 'name'], $row->getPublicProperties());
        self::assertSame(StringType::fromData([])->toData()->toString(), $row->getPublicProperty('name')->toString());
    }

    public function test_it_should_fetch_documents_with_properties_having_constraint(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $fixtures
            ->newDocument()
            ->withTextProperty('name')->required()->endProperty()
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindAllMyDocuments());
        self::assertCount(1, $result = $query->getResult());
        self::assertContainsOnlyInstancesOf(MyReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame(['id', 'name'], $row->getPublicProperties());
        self::assertSame(StringType::fromData([])->toData()->toString(), $row->getPublicProperty('name')->toString());
    }
}
