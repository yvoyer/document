<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentTypeWasCreated implements DocumentEvent
{
    private string $id;
    private DocumentName $name;
    private string $createdBy;
    private string $createdAt;

    public function __construct(
        DocumentTypeId $id,
        DocumentName $name,
        DocumentOwner $owner,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id->toString();
        $this->name = $name;
        $this->createdBy = $owner->toString();
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
    }

    final public function documentId(): DocumentTypeId
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

    final public function updatedAt(): DateTimeInterface
    {
        return new DateTimeImmutable($this->createdAt);
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentTypeId::fromString($payload['id']),
            DocumentName::fromSerializedString($payload['name']),
            MemberId::fromString($payload['createdBy']),
            new DateTimeImmutable($payload['createdAt'])
        );
    }
}
