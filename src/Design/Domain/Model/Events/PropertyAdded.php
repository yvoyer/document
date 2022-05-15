<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyAdded implements DocumentEvent
{
    private DocumentId $document;
    private PropertyName $name;
    private TypeData $type;
    private DocumentOwner $createdBy;
    private string $createdAt;

    public function __construct(
        DocumentId $document,
        PropertyName $name,
        PropertyType $type,
        DocumentOwner $createdBy,
        DateTimeInterface $createdAt
    ) {
        $this->document = $document;
        $this->name = $name;
        $this->type = $type->toData();
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
    }

    public function documentId(): DocumentId
    {
        return $this->document;
    }

    public function name(): PropertyName
    {
        return $this->name;
    }

    public function type(): PropertyType
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
        var_dump($payload);
        return new self(
            DocumentId::fromString($payload['document']),
            PropertyName::fromSerializedString($payload['name']),
            TypeData::fromString($payload['type'])->createType()
        );
    }
}
