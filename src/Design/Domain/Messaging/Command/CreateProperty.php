<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateProperty implements Command
{
    private DocumentTypeId $typeId;
    private PropertyCode $code;
    private PropertyName $name;
    private PropertyType $type;
    private AuditDateTime $createdAt;

    public function __construct(
        DocumentTypeId $typeId,
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type,
        AuditDateTime $createdAt
    ) {
        $this->typeId = $typeId;
        $this->code = $code;
        $this->name = $name;
        $this->type = $type;
        $this->createdAt = $createdAt;
    }

    final public function typeId(): DocumentTypeId
    {
        return $this->typeId;
    }

    final public function code(): PropertyCode
    {
        return $this->code;
    }

    final public function name(): PropertyName
    {
        return $this->name;
    }

    final public function type(): PropertyType
    {
        return $this->type;
    }

    final public function createdAt(): AuditDateTime
    {
        return $this->createdAt;
    }
}
