<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory;

use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class RecordCollection implements RecordRepository, \Countable
{
    /**
     * @var DocumentRecord[]
     */
    private $records = [];

    /**
     * @param DocumentId $id
     * @param DocumentRecord $record
     */
    public function saveRecord(DocumentId $id, DocumentRecord $record): void
    {
        $this->records[$id->toString()] = $record;
    }

    /**
     * @param DocumentTypeId $id
     *
     * @return DocumentRecord[]
     */
    public function allRecordsOfDocument(DocumentTypeId $id): array
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
     * @param DocumentId $id
     *
     * @return DocumentRecord
     * @throws EntityNotFoundException
     */
    public function getRecordWithIdentity(DocumentId $id): DocumentRecord
    {
        if (! $this->recordExists($id)) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->records[$id->toString()];
    }

    /**
     * @param DocumentId $id
     *
     * @return bool
     */
    public function recordExists(DocumentId $id): bool
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
