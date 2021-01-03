<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\Behavior\BehaviorSubject;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Events;
use Star\Component\DomainEvent\AggregateRoot;

class DocumentAggregate extends AggregateRoot implements DocumentDesigner, BehaviorSubject
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @var DocumentConstraint[]
     */
    private $constraints = [];

    public function getSchema(): SchemaMetadata
    {
        return $this->schema;
    }

    public function getIdentity(): DocumentId
    {
        return $this->schema->getIdentity();
    }

    public function addProperty(PropertyName $name, PropertyType $type): void
    {
        $this->mutate(new Events\PropertyAdded($this->getIdentity(), $name, $type));
    }

    public function addPropertyConstraint(
        PropertyName $name,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->mutate(
            new Events\PropertyConstraintWasAdded($this->getIdentity(), $name, $constraintName, $constraint)
        );
    }

    public function removePropertyConstraint(PropertyName $name, string $constraintName): void
    {
        $this->mutate(
            new Events\PropertyConstraintWasRemoved($this->getIdentity(), $name, $constraintName)
        );
    }

    public function addPropertyParameter(
        PropertyName $name,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->mutate(new Events\PropertyParameterAdded($this->getIdentity(), $name, $parameterName, $parameter));
    }

    public function addDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        $this->mutate(
            new Events\DocumentConstraintRegistered($this->getIdentity(), $name, $constraint)
        );
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        $this->schema->acceptDocumentVisitor($visitor);

        foreach ($this->constraints as $name => $constraint) {
            $visitor->visitDocumentConstraint($name, $constraint);
        }
    }

    protected function onDocumentCreated(Events\DocumentCreated $event): void
    {
        $this->schema = new DocumentSchema($event->documentId());
    }

    protected function onPropertyAdded(Events\PropertyAdded $event): void
    {
        $type = $event->type();

        $this->schema->addProperty($event->name()->toString(), $type);
    }

    protected function onDocumentConstraintRegistered(Events\DocumentConstraintRegistered $event): void
    {
        $constraint = $event->constraint();
        $constraint->onRegistered($this);
        $this->constraints[$event->constraintName()] = $constraint;
    }

    protected function onPropertyParameterAdded(Events\PropertyParameterAdded $event): void
    {
        $this->schema->addParameter(
            $event->property()->toString(),
            $event->parameterName(),
            $event->parameter()
        );
    }

    protected function onPropertyConstraintWasAdded(Events\PropertyConstraintWasAdded $event): void
    {
        $this->schema->addPropertyConstraint(
            $event->propertyName()->toString(),
            $event->constraintName(),
            $event->constraint()
        );
    }

    protected function onPropertyConstraintWasRemoved(Events\PropertyConstraintWasRemoved $event): void
    {
        $this->schema->removePropertyConstraint($event->propertyName()->toString(), $event->constraintName());
    }

    /**
     * @param DocumentId $id
     * @return DocumentAggregate
     */
    public static function draft(DocumentId $id): DocumentAggregate
    {
        /**
         * @var DocumentAggregate $aggregate
         */
        $aggregate = static::fromStream(
            [
                new Events\DocumentCreated($id),
            ]
        );

        return $aggregate;
    }
}
