<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\Behavior\BehaviorSubject;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Events;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\DomainEvent\AggregateRoot;

class DocumentTypeAggregate extends AggregateRoot implements DocumentDesigner, BehaviorSubject
{
    private DocumentName $name;
    private DocumentSchema $schema;
    private DocumentOwner $owner;
    private string $defaultLocale;

    /**
     * @var DocumentConstraint[]
     */
    private array $constraints = [];

    public static function draft(
        DocumentTypeId $id,
        DocumentName $name,
        DocumentOwner $owner,
        DateTimeInterface $created_at
    ): DocumentTypeAggregate {
        /**
         * @var DocumentTypeAggregate $aggregate
         */
        $aggregate = static::fromStream(
            [
                new Events\DocumentTypeWasCreated($id, $name, $owner, $created_at),
            ]
        );

        return $aggregate;
    }

    public function getSchema(): SchemaMetadata
    {
        return $this->schema;
    }

    public function addProperty(
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type,
        DateTimeInterface $addedAt
    ): void {
        $this->mutate(
            new Events\PropertyWasAdded(
                $this->getIdentity(),
                $code,
                $name,
                $type,
                $this->owner,
                $addedAt
            )
        );
    }

    public function propertyExists(PropertyCode $code): bool
    {
        $this->acceptDocumentVisitor($visitor = new PropertyExtractor());

        return $visitor->hasProperty($code);
    }

    public function getIdentity(): DocumentTypeId
    {
        return $this->schema->getIdentity();
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    public function addPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint,
        DateTimeInterface $addedAt
    ): void
    {
        $this->mutate(
            new Events\PropertyConstraintWasAdded(
                $this->getIdentity(),
                $code,
                $constraintName,
                $constraint,
                $this->owner,
                $addedAt
            )
        );
    }

    public function documentConstraintExists(string $constraint): bool
    {
        $this->acceptDocumentVisitor($visitor = new PropertyExtractor());

        return $visitor->hasDocumentConstraint($constraint);
    }

    public function propertyConstraintExists(PropertyCode $code, string $constraint): bool
    {
        $this->acceptDocumentVisitor($visitor = new PropertyExtractor());

        return $visitor->getProperty($code)->hasConstraint($constraint);
    }

    public function removePropertyConstraint(
        PropertyCode $code,
        string $constraintName
    ): void {
        $this->mutate(
            new Events\PropertyConstraintWasRemoved($this->getIdentity(), $code, $constraintName)
        );
    }

    public function addPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter,
        DateTimeInterface $addedAt
    ): void {
        $this->mutate(
            new Events\PropertyParameterWasAdded(
                $this->getIdentity(),
                $code,
                $parameterName,
                $parameter,
                $this->owner,
                $addedAt
            )
        );
    }

    public function addDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        $this->mutate(
            new Events\DocumentConstraintWasRegistered($this->getIdentity(), $name, $constraint)
        );
    }

    public function acceptDocumentVisitor(DocumentTypeVisitor $visitor): void
    {
        $this->schema->acceptDocumentTypeVisitor($visitor);

        foreach ($this->constraints as $name => $constraint) {
            $visitor->visitDocumentConstraint($name, $constraint);
        }
    }

    protected function onDocumentTypeWasCreated(Events\DocumentTypeWasCreated $event): void
    {
        $this->schema = new DocumentSchema($event->documentId());
        $this->defaultLocale = $event->name()->locale();
        $this->name = $event->name();
        $this->owner = $event->updatedBy();
    }

    protected function onPropertyWasAdded(Events\PropertyWasAdded $event): void
    {
        $type = $event->type();

        $this->schema->addProperty($event->code(), $event->name(), $type);
    }

    protected function onDocumentConstraintWasRegistered(Events\DocumentConstraintWasRegistered $event): void
    {
        $constraint = $event->constraint();
        $constraint->onRegistered($this);
        $this->constraints[$event->constraintName()] = $constraint;
    }

    protected function onPropertyParameterWasAdded(Events\PropertyParameterWasAdded $event): void
    {
        $this->schema->addParameter(
            $event->property(),
            $event->parameterName(),
            $event->parameter()
        );
    }

    protected function onPropertyConstraintWasAdded(Events\PropertyConstraintWasAdded $event): void
    {
        $this->schema->addPropertyConstraint(
            $event->propertyCode(),
            $event->constraintName(),
            $event->constraint()
        );
    }

    protected function onPropertyConstraintWasRemoved(Events\PropertyConstraintWasRemoved $event): void
    {
        $this->schema->removePropertyConstraint($event->propertyCode(), $event->constraintName());
    }
}
