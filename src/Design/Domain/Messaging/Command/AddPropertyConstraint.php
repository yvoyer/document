<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\DomainEvent\Messaging\Command;

final class AddPropertyConstraint implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var string
     */
    private $constraintName;

    /**
     * @var mixed[]
     */
    private $constraintData;

    /**
     * @param DocumentId $documentId
     * @param PropertyName $name
     * @param string $constraintName
     * @param mixed[] $constraintData
     */
    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        string $constraintName,
        array $constraintData
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->constraintName = $constraintName;
        $this->constraintData = $constraintData;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function name(): PropertyName
    {
        return $this->name;
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
}
