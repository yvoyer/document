<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateProperty implements Command
{
    private DocumentId $documentId;
    private PropertyName $name;
    private PropertyType $type;
    private DocumentOwner $createdBy;
    private DateTimeInterface $createdAt;

    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        PropertyType $type,
        DocumentOwner $createdBy,
        DateTimeInterface $createdAt
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->type = $type;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function name(): PropertyName
    {
        return $this->name;
    }

    public function type(): PropertyType
    {
        return $this->type;
    }

    final public function createdBy(): DocumentOwner
    {
        return $this->createdBy;
    }

    final public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
