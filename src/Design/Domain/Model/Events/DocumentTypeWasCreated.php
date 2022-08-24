<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentTypeWasCreated implements DocumentTypeEvent
{
    private string $id;
    private DocumentName $name;
    private string $createdBy;
    private AuditDateTime $createdAt;

    public function __construct(
        DocumentTypeId $id,
        DocumentName $name,
        DocumentOwner $owner,
        AuditDateTime $createdAt
    ) {
        $this->id = $id->toString();
        $this->name = $name;
        $this->createdBy = $owner->toString();
        $this->createdAt = $createdAt;
    }

    final public function typeId(): DocumentTypeId
    {
        return DocumentTypeId::fromString($this->id);
    }

    final public function name(): DocumentName
    {
        return $this->name;
    }

    final public function updatedBy(): DocumentOwner
    {
        return MemberId::fromString($this->createdBy);
    }

    final public function updatedAt(): AuditDateTime
    {
        return $this->createdAt;
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentTypeId::fromString($payload['id']),
            DocumentName::fromSerializedString($payload['name']),
            MemberId::fromString($payload['createdBy']),
            AuditDateTime::fromString($payload['createdAt'])
        );
    }
}
