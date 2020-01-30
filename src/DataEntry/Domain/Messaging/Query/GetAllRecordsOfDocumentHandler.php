<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Query;

use React\Promise\Deferred;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;

final class GetAllRecordsOfDocumentHandler
{
    /**
     * @var RecordRepository
     */
    private $records;

    public function __construct(RecordRepository $records)
    {
        $this->records = $records;
    }

    public function __invoke(GetAllRecordsOfDocument $message, Deferred $deferred): void
    {
        $deferred->resolve(
            array_map(
                function (DocumentRecord $record) {
                    return new RecordRow($record);
                },
                $this->records->allRecordsOfDocument($message->documentId())
            )
        );
    }
}
