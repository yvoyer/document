<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Audit\Domain\Model\UpdatedBy;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\Behavior\BehaviorSubject;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Events;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Translation\Domain\Model\Strategy\ReturnDefaultValue;
use Star\Component\Document\Translation\Domain\Model\Strategy\ThrowExceptionWhenNotDefined;
use Star\Component\Document\Translation\Domain\Model\TranslatedField;
use Star\Component\Document\Translation\Domain\Model\TranslationLocale;
use Star\Component\DomainEvent\AggregateRoot;
use function var_dump;

class DocumentTypeAggregate extends AggregateRoot implements DocumentDesigner, BehaviorSubject
{
    private TranslatedField $name;
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
        AuditDateTime $created_at
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

    public function getName(TranslationLocale $locale): DocumentName
    {
        return DocumentName::fromLocalizedString(
            $this->name->toTranslatedString($locale->toString()),
            $locale->toString()
        );
    }

    public function getSchema(): SchemaMetadata
    {
        return $this->schema;
    }

    public function addProperty(
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type,
        AuditDateTime $addedAt
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
        string $constraintAlias,
        PropertyConstraint $constraint,
        AuditDateTime $addedAt
    ): void
    {
        $this->mutate(
            new Events\PropertyConstraintWasAdded(
                $this->getIdentity(),
                $code,
                $constraintAlias,
                $constraint->toData(),
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

    public function rename(DocumentName $name, AuditDateTime $renamedAt, UpdatedBy $renamedBy): void
    {
        $this->mutate(
            new Events\DocumentTypeWasRenamed(
                $this->getIdentity(),
                $this->getName(TranslationLocale::fromString($name->locale())),
                $name,
                $renamedAt,
                $renamedBy
            )
        );
    }

    public function addPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter,
        AuditDateTime $addedAt
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
            new Events\DocumentTypeConstraintWasRegistered($this->getIdentity(), $name, $constraint)
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
        $this->schema = new DocumentSchema($event->typeId());
        $this->defaultLocale = $event->name()->locale();
        $this->name = TranslatedField::withSingleTranslation(
            'name',
            $event->name()->toString(),
            $event->name()->locale(),
            new ReturnDefaultValue($event->name()->toString())
        );
        $this->owner = $event->updatedBy();
    }

    protected function onPropertyWasAdded(Events\PropertyWasAdded $event): void
    {
        $type = $event->type();

        $this->schema->addProperty($event->code(), $event->name(), $type);
    }

    protected function onDocumentTypeConstraintWasRegistered(Events\DocumentTypeConstraintWasRegistered $event): void
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
            $event->constraintAlias(),
            $event->constraintData()->createPropertyConstraint()
        );
    }

    protected function onPropertyConstraintWasRemoved(Events\PropertyConstraintWasRemoved $event): void
    {
        $this->schema->removePropertyConstraint($event->propertyCode(), $event->constraintName());
    }

    protected function onDocumentTypeWasRenamed(Events\DocumentTypeWasRenamed $event): void
    {
        $this->name = $this->name->update(
            $event->newName()->toString(),
            $event->newName()->locale()
        );
    }
}
