<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\Templating\NotNamedDocument;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateDocument implements Command
{
    private DocumentId $id;
    private DocumentName $name;
    private DocumentOwner $owner;
    private DateTimeInterface $createdAt;

    public function __construct(
        DocumentId $id,
        DocumentName $name,
        DocumentOwner $owner,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = $createdAt;
    }

    final public function documentId(): DocumentId
    {
        return $this->id;
    }

    final public function name(): DocumentName
    {
        return $this->name;
    }

    final public function owner(): DocumentOwner
    {
        return $this->owner;
    }

    final public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    final public static function emptyDocument(
        DocumentId $id,
        DocumentOwner $owner,
        DateTimeInterface $createdAt
    ): self {
        return new self($id, new NotNamedDocument(), $owner, $createdAt);
    }
}
