<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateDocumentType implements Command
{
    private DocumentTypeId $id;
    private DocumentName $name;
    private DocumentOwner $owner;
    private DateTimeInterface $createdAt;

    public function __construct(
        DocumentTypeId $typeId,
        DocumentName $name,
        DocumentOwner $owner,
        DateTimeInterface $createdAt
    ) {
        $this->id = $typeId;
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = $createdAt;
    }

    final public function typeId(): DocumentTypeId
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
}
