<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;

final class DocumentConstraintRegistered implements DocumentEvent
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var DocumentConstraint
     */
    private $constraint;

    public function __construct(DocumentId $id, string $name, DocumentConstraint $constraint)
    {
        $this->id = $id;
        $this->name = $name;
        $this->constraint = $constraint;
    }

    public function documentId(): DocumentId
    {
        return $this->id;
    }

    public function constraintName(): string
    {
        return $this->name;
    }

    public function constraint(): DocumentConstraint
    {
        return $this->constraint;
    }
}
