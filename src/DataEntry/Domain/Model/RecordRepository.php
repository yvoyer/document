<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Identity\Exception\EntityNotFoundException;

interface RecordRepository
{
    /**
     * @param DocumentId $id
     *
     * @return DocumentRecord
     * @throws EntityNotFoundException
     */
    public function getRecordWithIdentity(DocumentId $id): DocumentRecord;

    /**
     * @param DocumentId $id
     *
     * @return bool
     */
    public function recordExists(DocumentId $id): bool;

    /**
     * @param DocumentId $id
     * @param DocumentRecord $record
     */
    public function saveRecord(DocumentId $id, DocumentRecord $record): void;

    /**
     * @param DocumentTypeId $id
     *
     * @return DocumentRecord[]
     */
    public function allRecordsOfDocument(DocumentTypeId $id): array;
}
