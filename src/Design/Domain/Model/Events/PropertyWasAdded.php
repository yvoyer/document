<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyWasAdded implements DocumentTypeEvent
{
    private DocumentTypeId $typeId;
    private PropertyCode $code;
    private PropertyName $name;
    private TypeData $type;
    private DocumentOwner $createdBy;
    private AuditDateTime $createdAt;

    public function __construct(
        DocumentTypeId $document,
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type,
        DocumentOwner $createdBy,
        AuditDateTime $createdAt
    ) {
        $this->typeId = $document;
        $this->code = $code;
        $this->name = $name;
        $this->type = $type->toData();
        $this->createdBy = $createdBy;
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
        return $this->type->createType();
    }

    final public function updatedBy(): DocumentOwner
    {
        return $this->createdBy;
    }

    final public function updatedAt(): AuditDateTime
    {
        return $this->createdAt;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentTypeId::fromString($payload['typeId']),
            PropertyCode::fromString($payload['code']),
            PropertyName::fromSerializedString($payload['name']),
            TypeData::fromString($payload['type'])->createType(),
            new MemberId($payload['createdBy']),
            AuditDateTime::fromString($payload['createdAt'])
        );
    }
}
