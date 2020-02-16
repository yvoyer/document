<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Events;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidateProperty;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\DomainEvent\AggregateRoot;
use function array_key_exists;
use function array_keys;
use function array_merge;

final class RecordAggregate extends AggregateRoot implements DocumentRecord
{
    /**
     * @var RecordId
     */
    private $id;

    /**
     * @var SchemaMetadata
     */
    private $schema;

    /**
     * @var RecordValue[]
     */
    private $values = [];

    /**
     * @param RecordId $id
     * @param SchemaMetadata $schema
     * @param RecordValue[] $values Indexed by the string representation of the property name
     * @param StrategyToHandleValidationErrors|null $strategy
     * @return RecordAggregate
     */
    public static function withValues(
        RecordId $id,
        SchemaMetadata $schema,
        array $values = [],
        StrategyToHandleValidationErrors $strategy = null
    ): RecordAggregate {
        /**
         * @var RecordAggregate $record
         */
        $record = self::fromStream([new Events\RecordWasCreated($id, $schema->toString())]);
        $schema->acceptDocumentVisitor($visitor = new HandleDefaultValues(...array_keys($values)));

        $values = array_merge($values, $visitor->allDefaultValues());
        foreach ($values as $property => $value) {
            $record->setValue($property, $value, $strategy);
        }

        return $record;
    }

    public function getIdentity(): RecordId
    {
        return $this->id;
    }

    public function getDocumentId(): DocumentId
    {
        return $this->schema->getIdentity();
    }

    public function setValue(
        string $propertyName,
        RecordValue $value,
        StrategyToHandleValidationErrors $strategy = null
    ): void {
        if (! $strategy) {
            $strategy = new AlwaysThrowExceptionOnValidationErrors();
        }

        $property = $this->schema->getPropertyMetadata($propertyName);
        if (! $property->supportsType($value)) {
            throw $property->generateExceptionForNotSupportedTypeForValue(
                $propertyName,
                $value
            );
        }

        if (! $property->supportsValue($value)) {
            throw $property->generateExceptionForNotSupportedValue($propertyName, $value);
        }

        $errors = new ErrorList();
        $this->schema->acceptDocumentVisitor(new ValidateProperty($propertyName, $value, $errors));

        if ($errors->hasErrors()) {
            $strategy->handleFailure($errors);
        }

        if (array_key_exists($propertyName, $this->values)) {
            $oldValue = $this->values[$propertyName];
            if ($oldValue->toString() !== $value->toString()) {
                $this->mutate(
                    new Events\PropertyValueWasChanged(
                        $this->getIdentity(),
                        $this->getDocumentId(),
                        PropertyName::fromString($propertyName),
                        $oldValue,
                        $value
                    )
                );
            }
        } else {
            $this->mutate(
                new Events\PropertyValueWasSet(
                    $this->getIdentity(),
                    $this->getDocumentId(),
                    PropertyName::fromString($propertyName),
                    $value
                )
            );
        }

        $this->values[$propertyName] = $property->toWriteFormat($value);
    }

    public function getValue(string $propertyName): RecordValue
    {
        $property = $this->schema->getPropertyMetadata($propertyName);

        return $property->toReadFormat($this->values[$propertyName]);
    }

    public function executeAction(RecordAction $action): void
    {
        $action->perform($this->schema, $this);
        $this->mutate(new Events\ActionWasPerformed($this->getIdentity(), $action));
    }

    protected function onActionWasPerformed(Events\ActionWasPerformed $event): void
    {
    }

    protected function onRecordWasCreated(Events\RecordWasCreated $event): void
    {
        $this->id = $event->recordId();
        $this->schema = DocumentSchema::fromJsonString($event->schema());
    }

    protected function onPropertyValueWasSet(Events\PropertyValueWasSet $event): void
    {
    }

    protected function onPropertyValueWasChanged(Events\PropertyValueWasChanged $event): void
    {
    }
}
