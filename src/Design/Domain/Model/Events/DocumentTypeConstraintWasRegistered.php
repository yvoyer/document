<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentTypeConstraintWasRegistered implements DocumentTypeEvent
{
    private DocumentTypeId $id;
    private string $name; // todo constraint name
    private DocumentConstraint $constraint;

    public function __construct(DocumentTypeId $id, string $name, DocumentConstraint $constraint)
    {
        $this->id = $id;
        $this->name = $name;
        $this->constraint = $constraint;
    }

    public function typeId(): DocumentTypeId
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

    public function updatedAt(): AuditDateTime
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function updatedBy(): DocumentOwner
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
