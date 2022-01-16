<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\Templating\NamedDocument;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentCreated implements DocumentEvent
{
    private string $id;
    private DocumentName $name;
    private string $createdBy;
    private string $createdAt;

    public function __construct(
        DocumentId        $id,
        DocumentName      $name,
        DocumentOwner     $owner,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id->toString();
        $this->name = $name;
        $this->createdBy = $owner->toString();
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
    }

    public function documentId(): DocumentId
    {
        return DocumentId::fromString($this->id);
    }

    public function name(): DocumentName
    {
        return $this->name;
    }

    final public function createdBy(): DocumentOwner
    {
        return MemberId::fromString($this->createdBy);
    }

    final public function createdAt(): DateTimeInterface
    {
        return new DateTimeImmutable($this->createdAt);
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentId::fromString($payload['id']),
            new NamedDocument($payload['name']),
            MemberId::fromString($payload['createdBy']),
            new DateTimeImmutable($payload['createdAt'])
        );
    }
}
