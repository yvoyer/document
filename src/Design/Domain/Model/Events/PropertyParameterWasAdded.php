<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyParameterWasAdded implements DocumentEvent
{
    private DocumentTypeId $documentId;
    private PropertyCode $property;
    private string $parameterName;
    private PropertyParameter $parameter;
    private DocumentOwner $addedBy;
    private DateTimeInterface $addedAt;

    public function __construct(
        DocumentTypeId $documentId,
        PropertyCode $property,
        string $parameterName,
        PropertyParameter $parameter,
        DocumentOwner $addedBy,
        DateTimeInterface $addedAt
    ) {
        $this->documentId = $documentId;
        $this->property = $property;
        $this->parameterName = $parameterName;
        $this->parameter = $parameter;
        $this->addedBy = $addedBy;
        $this->addedAt = $addedAt;
    }

    public function documentId(): DocumentTypeId
    {
        return $this->documentId;
    }

    public function property(): PropertyCode
    {
        return $this->property;
    }

    public function parameterName(): string
    {
        return $this->parameterName;
    }

    public function parameter(): PropertyParameter
    {
        return $this->parameter;
    }

    final public function updatedBy(): DocumentOwner
    {
        return $this->addedBy;
    }

    final public function updatedAt(): DateTimeInterface
    {
        return $this->addedAt;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        \var_dump($payload);
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
