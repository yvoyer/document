<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

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
     * @var PropertyConstraint
     */
    private $constraint;

    /**
     * @param DocumentId $documentId
     * @param PropertyName $name
     * @param string $constraintName
     * @param PropertyConstraint $constraint
     */
    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        $constraintName,
        PropertyConstraint $constraint
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->constraintName = $constraintName;
        $this->constraint = $constraint;
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
     * @return PropertyConstraint
     */
    public function constraint(): PropertyConstraint
    {
        return $this->constraint;
    }

    /**
     * @param string $documentId
     * @param string $propertyName
     * @param string $constraintName
     * @param PropertyConstraint $constraint
     *
     * @return AddPropertyConstraint
     */
    public static function fromString(
        string $documentId,
        string $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): self {
        return new self(
            new DocumentId($documentId),
            new PropertyName($propertyName),
            $constraintName,
            $constraint
        );
    }
}
