<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
use Star\Component\Document\Design\Domain\Model\Events;
use Star\Component\DomainEvent\AggregateRoot;

final class DocumentAggregate extends AggregateRoot implements DocumentDesigner
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @var DocumentState
     */
    private $state;

    public function getSchema(): DocumentSchema
    {
        return $this->schema;
    }

    public function getIdentity(): DocumentId
    {
        return $this->schema->getIdentity();
    }

    public function publish(): void
    {
        $this->mutate(new Events\DocumentPublished($this->getIdentity()));
    }

    public function addProperty(PropertyName $name, PropertyType $type, PropertyConstraint $constraint): void
    {
        $this->mutate(new Events\PropertyAdded($name, $type, $constraint));
    }

    public function addPropertyConstraint(
        PropertyName $name,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->schema->addConstraint($name->toString(), $constraintName, $constraint);
    }

    public function addPropertyTransformer(PropertyName $property, TransformerIdentifier $identifier): void
    {
        $this->mutate(new Events\TransformerAddedOnProperty($this->getIdentity(), $property, $identifier));
    }

    public function setDocumentConstraint(DocumentConstraint $constraint): void
    {
        $this->mutate(new Events\DocumentConstraintRegistered($this->getIdentity(), $constraint));
    }

    public function removeConstraint(PropertyName $name, string $constraintName): void
    {
        $this->schema->getDefinition($name->toString())->removeConstraint($constraintName);
    }

    public function isPublished(): bool
    {
        return $this->state->isPublished();
    }

    public function getPropertyDefinition(PropertyName $name): PropertyDefinition
    {
        return $this->schema->getDefinition($name->toString());
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        $this->schema->acceptDocumentVisitor($visitor);
    }

    protected function onDocumentCreated(Events\DocumentCreated $event): void
    {
        $this->schema = new DocumentSchema($event->documentId());
        $this->state = new DocumentState();
    }

    protected function onDocumentPublished(Events\DocumentPublished $event): void
    {
        $this->state = $this->state->publish();
# todo        $this->constraints->onPublish($this);
    }

    protected function onPropertyAdded(Events\PropertyAdded $event): void
    {
        $this->schema->addProperty($event->name()->toString(), $event->type());
    }

    protected function onTransformerAddedOnProperty(Events\TransformerAddedOnProperty $event): void
    {
        $this->schema->getDefinition($event->property()->toString())->addTransformer($event->identifier());
    }

    protected function onDocumentConstraintRegistered(Events\DocumentConstraintRegistered $event): void
    {
        $this->constraints = $event->constraint();
    }

    public static function draft(DocumentId $id): self
    {
        return self::fromStream([new Events\DocumentCreated($id)]);
    }
}
