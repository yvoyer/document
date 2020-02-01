<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Exception\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\Events;
use Star\Component\DomainEvent\AggregateRoot;

final class DocumentDesignerAggregate extends AggregateRoot implements DocumentDesigner
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentState
     */
    private $state;

    /**
     * @var DocumentProperty[]
     */
    private $properties = [];

    /**
     * @var DocumentConstraint
     */
    private $constraints;

    public function getIdentity(): DocumentId
    {
        return $this->id;
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
        $this->getProperty($name)->addConstraint($constraintName, $constraint);
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
        $this->getProperty($name)->removeConstraint($constraintName);
    }

    public function isPublished(): bool
    {
        return $this->state->isPublished();
    }

    public function getPropertyDefinition(PropertyName $name): PropertyDefinition
    {
        return $this->getProperty($name)->getDefinition();
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        $visitor->visitDocument($this->getIdentity());
        foreach ($this->properties as $property) {
            $property->acceptDocumentVisitor($visitor);
        }

        $visitor->visitEnded($this->properties);
    }

    protected function onDocumentCreated(Events\DocumentCreated $event): void
    {
        $this->id = $event->documentId();
        $this->state = new DocumentState();
        $this->constraints = new NoConstraint();
    }

    protected function onDocumentPublished(Events\DocumentPublished $event): void
    {
        $this->state = $this->state->publish();
        $this->constraints->onPublish($this);
    }

    protected function onPropertyAdded(Events\PropertyAdded $event): void
    {
        $definition = new PropertyDefinition($event->name(), $event->type());
        $property = new DocumentProperty($this, $definition);

        $this->properties[$event->name()->toString()] = $property;
    }

    protected function onTransformerAddedOnProperty(Events\TransformerAddedOnProperty $event): void
    {
        $this->getProperty($event->property())->addTransformer($event->identifier());
    }

    protected function onDocumentConstraintRegistered(Events\DocumentConstraintRegistered $event): void
    {
        $this->constraints = $event->constraint();
    }

    private function getProperty(PropertyName $name): DocumentProperty
    {
        if (! $this->hasProperty($name)) {
            throw new ReferencePropertyNotFound($name);
        }

        return $this->properties[$name->toString()];
    }

    private function hasProperty(PropertyName $name): bool
    {
        return \array_key_exists($name->toString(), $this->properties);
    }

    public static function draft(DocumentId $id): self
    {
        return self::fromStream([new Events\DocumentCreated($id)]);
    }
}
