<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Audit\Domain\Model\UpdatedBy;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentTypeWasRenamed implements DocumentTypeEvent
{
    private DocumentTypeId $typeId;
    private DocumentName $oldName;
    private DocumentName $newName;
    private AuditDateTime $renamedAt;
    private UpdatedBy $renamedBy;

    public function __construct(
        DocumentTypeId $typeId,
        DocumentName $oldName,
        DocumentName $newName,
        AuditDateTime $renamedAt,
        UpdatedBy $renamedBy
    ) {
        $this->typeId = $typeId;
        $this->oldName = $oldName;
        $this->newName = $newName;
        $this->renamedAt = $renamedAt;
        $this->renamedBy = $renamedBy;
    }

    final public function typeId(): DocumentTypeId
    {
        return $this->typeId;
    }

    final public function oldName(): DocumentName
    {
        return $this->oldName;
    }

    final public function newName(): DocumentName
    {
        return $this->newName;
    }

    final public function updatedAt(): AuditDateTime
    {
        return $this->renamedAt;
    }

    final public function updatedBy(): UpdatedBy
    {
        return $this->renamedBy;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
