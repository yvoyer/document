<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\DomainEvent\Messaging\Command;

final class AddPropertyConstraint implements Command
{
    private DocumentTypeId $documentId;
    private PropertyCode $code;
    private string $constraintName;
    private DateTimeInterface $addedAt;

    /**
     * @var mixed[]
     */
    private array $constraintData;

    /**
     * @param DocumentTypeId $typeId
     * @param PropertyCode $code
     * @param string $constraintName
     * @param mixed[] $constraintData
     * @param DateTimeInterface $addedAt
     */
    public function __construct(
        DocumentTypeId $typeId,
        PropertyCode $code,
        string $constraintName,
        array $constraintData,
        DateTimeInterface $addedAt
    ) {
        $this->documentId = $typeId;
        $this->code = $code;
        $this->constraintName = $constraintName;
        $this->constraintData = $constraintData;
        $this->addedAt = $addedAt;
    }

    public function typeId(): DocumentTypeId
    {
        return $this->documentId;
    }

    public function code(): PropertyCode
    {
        return $this->code;
    }

    public function constraintName(): string
    {
        return $this->constraintName;
    }

    /**
     * @return mixed[]
     */
    public function constraintData(): array
    {
        return $this->constraintData;
    }

    final public function addedAt(): DateTimeInterface
    {
        return $this->addedAt;
    }
}
