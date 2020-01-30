<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\DomainEvent\DomainEvent;

final class DocumentConstraintRegistered implements DomainEvent
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentConstraint
     */
    private $constraint;

    public function __construct(DocumentId $id, DocumentConstraint $constraint)
    {
        $this->id = $id;
        $this->constraint = $constraint;
    }

    public function documentId(): DocumentId
    {
        return $this->id;
    }

    public function constraint(): DocumentConstraint
    {
        return $this->constraint;
    }
}
