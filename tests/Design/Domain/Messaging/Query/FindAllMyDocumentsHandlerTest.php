<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class FindAllMyDocumentsHandlerTest extends RegressionTestCase
{
    public function test_it_should_fetch_documents_without_properties(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $document = $fixtures
            ->newDocument($memberId)
            ->getDocumentId();

        $fixtures->dispatchQuery($query = new FindAllMyDocuments($memberId, 'en'));
        self::assertCount(1, $result = $query->getResultArray());
        self::assertContainsOnlyInstancesOf(ReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame($document->toString(), $row->getDocumentId());
        self::assertSame('default-name', $row->getDocumentName());
        self::assertSame($memberId->toString(), $row->getOwnerId());
        self::assertStringContainsString('username-', $row->getOwnerName());
        self::assertSame(date('Y-m-d'), $row->getCreatedAt()->format('Y-m-d'));
        self::assertSame(date('Y-m-d'), $row->getUpdatedAt()->format('Y-m-d'));
    }
}
