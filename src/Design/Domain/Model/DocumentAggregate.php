<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
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

    /**
     * @var DocumentConstraint
     */
    private $constraint;

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

    public function addProperty(
        PropertyName $name,
        PropertyType $type,
        PropertyConstraint $constraint = null,
        PropertyParameter $parameter = null
    ): void {
        $this->mutate(new Events\PropertyAdded($name, $type));
        if ($constraint instanceof PropertyConstraint) {
            $this->addPropertyConstraint($name, $constraint);
        }

        if ($parameter instanceof PropertyParameter) {
            $this->addPropertyParameter($name, $parameter);
        }
    }

    public function addPropertyConstraint(PropertyName $name, PropertyConstraint $constraint): void
    {
        $this->schema->addConstraint($name->toString(), $constraint);
    }

    public function addPropertyParameter(PropertyName $name, PropertyParameter $parameter): void
    {
        $this->mutate(new Events\PropertyParameterAdded($this->getIdentity(), $name, $parameter));
    }

    public function setDocumentConstraint(DocumentConstraint $constraint): void
    {
        $this->mutate(new Events\DocumentConstraintRegistered($this->getIdentity(), $constraint));
    }

    public function removeConstraint(PropertyName $name, string $constraintName): void
    {
        $this->schema->removeConstraint($name->toString(), $constraintName);
    }

    public function isPublished(): bool
    {
        return $this->state->isPublished();
    }

    public function getConstraint(): DocumentConstraint
    {
        return $this->constraint;
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
        $this->constraint = new NoConstraint();
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

    protected function onDocumentConstraintRegistered(Events\DocumentConstraintRegistered $event): void
    {
        $this->constraint = $event->constraint();
    }

    protected function onPropertyParameterAdded(Events\PropertyParameterAdded $event): void
    {
        $this->schema->addParameter($event->property()->toString(), $event->parameter());
    }

    public static function draft(DocumentId $id): self
    {
        return self::fromStream([new Events\DocumentCreated($id)]);
    }
}
