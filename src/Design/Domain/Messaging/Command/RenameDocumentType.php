<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Audit\Domain\Model\UpdatedBy;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class RenameDocumentType
{
    private DocumentTypeId $typeId;
    private DocumentTypeName $name;
    private AuditDateTime $renamedAt;
    private UpdatedBy $renamedBy;

    public function __construct(
        DocumentTypeId $typeId,
        DocumentTypeName $name,
        AuditDateTime $renamedAt,
        UpdatedBy $renamedBy
    ) {
        $this->typeId = $typeId;
        $this->name = $name;
        $this->renamedAt = $renamedAt;
        $this->renamedBy = $renamedBy;
    }

    final public function typeId(): DocumentTypeId
    {
        return $this->typeId;
    }

    final public function name(): DocumentTypeName
    {
        return $this->name;
    }

    final public function renamedAt(): AuditDateTime
    {
        return $this->renamedAt;
    }

    final public function renamedBy(): UpdatedBy
    {
        return $this->renamedBy;
    }
}
