<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class PropertyParameterWasAdded implements DocumentTypeEvent
{
    private DocumentTypeId $documentId;
    private PropertyCode $property;
    private string $parameterName;
    private PropertyParameter $parameter;
    private DocumentOwner $addedBy;
    private AuditDateTime $addedAt;

    public function __construct(
        DocumentTypeId $documentId,
        PropertyCode $property,
        string $parameterName,
        PropertyParameter $parameter,
        DocumentOwner $addedBy,
        AuditDateTime $addedAt
    ) {
        $this->documentId = $documentId;
        $this->property = $property;
        $this->parameterName = $parameterName;
        $this->parameter = $parameter;
        $this->addedBy = $addedBy;
        $this->addedAt = $addedAt;
    }

    public function typeId(): DocumentTypeId
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

    final public function updatedAt(): AuditDateTime
    {
        return $this->addedAt;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        \var_dump($payload);
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
