<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class RemovePropertyConstraint implements Command
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
     * @param DocumentId $documentId
     * @param PropertyName $name
     * @param string $constraintName
     */
    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        $constraintName
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->constraintName = $constraintName;
    }

    /**
     * @return DocumentId
     */
    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    /**
     * @return PropertyName
     */
    public function name(): PropertyName
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function constraintName(): string
    {
        return $this->constraintName;
    }

    /**
     * @param string $documentId
     * @param string $propertyName
     * @param string $constraintName
     *
     * @return RemovePropertyConstraint
     */
    public static function fromString(
        string $documentId,
        string $propertyName,
        string $constraintName
    ): self {
        return new self(
            new DocumentId($documentId),
            new PropertyName($propertyName),
            $constraintName
        );
    }
}
