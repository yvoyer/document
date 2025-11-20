<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateDocumentType implements Command
{
    private DocumentTypeId $id;
    private DocumentTypeName $name;
    private DocumentOwner $owner;
    private AuditDateTime $createdAt;

    public function __construct(
        DocumentTypeId $typeId,
        DocumentTypeName $name,
        DocumentOwner $owner,
        AuditDateTime $createdAt
    ) {
        $this->id = $typeId;
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = $createdAt;
    }

    final public function typeId(): DocumentTypeId
    {
        return $this->id;
    }

    final public function name(): DocumentTypeName
    {
        return $this->name;
    }

    final public function owner(): DocumentOwner
    {
        return $this->owner;
    }

    final public function createdAt(): AuditDateTime
    {
        return $this->createdAt;
    }
}
