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

    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        string $constraintName,
        PropertyConstraint $constraint
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->constraintName = $constraintName;
        $this->constraint = $constraint;
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

    public function constraint(): PropertyConstraint
    {
        return $this->constraint;
    }
}
