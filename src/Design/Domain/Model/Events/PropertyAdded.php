<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyAdded implements DocumentEvent
{
    /**
     * @var string
     */
    private $document;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    public function __construct(
        DocumentId $document,
        PropertyName $name,
        PropertyType $type
    ) {
        $this->document = $document->toString();
        $this->name = $name->toString();
        $this->type = $type->toData()->toString();
    }

    public function documentId(): DocumentId
    {
        return DocumentId::fromString($this->document);
    }

    public function name(): PropertyName
    {
        return PropertyName::fromString($this->name);
    }

    public function type(): PropertyType
    {
        return TypeData::fromString($this->type)->createType();
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentId::fromString($payload['document']),
            PropertyName::fromString($payload['name']),
            TypeData::fromString($payload['type'])->createType()
        );
    }
}
