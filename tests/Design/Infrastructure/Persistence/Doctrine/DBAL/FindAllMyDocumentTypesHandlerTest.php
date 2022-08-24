<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocumentTypes;
use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class FindAllMyDocumentTypesHandlerTest extends RegressionTestCase
{
    public function test_it_should_fetch_documents_without_properties(): void
    {
        $client = self::createTestClient();
        $fixtures = $client->createFixtureBuilder();
        $memberId = $fixtures->newMember()->getMemberId();

        $document = $fixtures
            ->newDocumentType('doc 1', 'en', $memberId)
            ->getDocumentTypeId();

        $fixtures->dispatchQuery($query = new FindAllMyDocumentTypes($memberId, 'en'));
        self::assertCount(1, $result = $query->getResultArray());
        self::assertContainsOnlyInstancesOf(ReadOnlyDocument::class, $result);

        $row = $result[0];
        self::assertSame($document->toString(), $row->getDocumentId());
        self::assertSame('doc 1', $row->getDocumentName());
        self::assertSame($memberId->toString(), $row->getOwnerId());
        self::assertStringContainsString('username-', $row->getOwnerName());
        self::assertSame(date('Y-m-d'), $row->getCreatedAt()->toDateFormat());
        self::assertSame(date('Y-m-d'), $row->getUpdatedAt()->toDateFormat());
    }
}
