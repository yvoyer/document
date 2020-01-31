<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class RecordCollection implements RecordRepository, \Countable
{
    /**
     * @var DocumentRecord[]
     */
    private $records = [];

    /**
     * @param RecordId $id
     * @param DocumentRecord $record
     */
    public function saveRecord(RecordId $id, DocumentRecord $record): void
    {
        $this->records[$id->toString()] = $record;
    }

    /**
     * @param DocumentId $id
     *
     * @return DocumentRecord[]
     */
    public function allRecordsOfDocument(DocumentId $id): array
    {
        return array_values(
            array_filter(
                $this->records,
                function (DocumentRecord $record) use ($id) {
                    return $id->matchIdentity($record->getDocumentId());
                }
            )
        );
    }

    /**
     * @param RecordId $id
     *
     * @return DocumentRecord
     * @throws EntityNotFoundException
     */
    public function getRecordWithIdentity(RecordId $id): DocumentRecord
    {
        if (! $this->recordExists($id)) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->records[$id->toString()];
    }

    /**
     * @param RecordId $id
     *
     * @return bool
     */
    public function recordExists(RecordId $id): bool
    {
        return isset($this->records[$id->toString()]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->records);
    }
}
