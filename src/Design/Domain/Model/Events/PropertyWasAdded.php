<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyWasAdded implements DocumentEvent
{
    private DocumentTypeId $document;
    private PropertyCode $code;
    private PropertyName $name;
    private TypeData $type;
    private DocumentOwner $createdBy;
    private string $createdAt;

    public function __construct(
        DocumentTypeId $document,
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type,
        DocumentOwner $createdBy,
        DateTimeInterface $createdAt
    ) {
        $this->document = $document;
        $this->code = $code;
        $this->name = $name;
        $this->type = $type->toData();
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
    }

    final public function documentId(): DocumentTypeId
    {
        return $this->document;
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

    final public function updatedAt(): DateTimeInterface
    {
        return new DateTimeImmutable($this->createdAt);
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentTypeId::fromString($payload['document']),
            PropertyCode::fromString($payload['code']),
            PropertyName::fromSerializedString($payload['name']),
            TypeData::fromString($payload['type'])->createType(),
            new MemberId($payload['created_by']),
            new DateTimeImmutable($payload['created_at'])
        );
    }
}
