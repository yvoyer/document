<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Identity\Exception\EntityNotFoundException;

interface RecordRepository
{
    /**
     * @param RecordId $id
     *
     * @return DocumentRecord
     * @throws EntityNotFoundException
     */
    public function getRecordWithIdentity(RecordId $id): DocumentRecord;

    /**
     * @param RecordId $id
     *
     * @return bool
     */
    public function recordExists(RecordId $id): bool;

    /**
     * @param RecordId $id
     * @param DocumentRecord $record
     */
    public function saveRecord(RecordId $id, DocumentRecord $record);

    /**
     * @param DocumentId $id
     *
     * @return DocumentRecord[]
     */
    public function allRecordsOfDocument(DocumentId $id): array;
}
