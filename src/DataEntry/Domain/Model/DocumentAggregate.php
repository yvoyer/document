<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Events;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidateProperty;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\DomainEvent\AggregateRoot;
use function array_key_exists;
use function array_keys;
use function array_merge;

final class DocumentAggregate extends AggregateRoot implements DocumentRecord
{
    private RecordId $id;
    private SchemaMetadata $schema;

    /**
     * @var RecordValue[]
     */
    private array $values = [];

    /**
     * @param RecordId $id
     * @param SchemaMetadata $schema
     * @param RecordValue[] $values Indexed by the property code of the property todo convert to object
     * @param StrategyToHandleValidationErrors|null $strategy
     * @return DocumentAggregate
     */
    public static function withValues(
        RecordId $id,
        SchemaMetadata $schema,
        array $values = [],
        StrategyToHandleValidationErrors $strategy = null
    ): DocumentAggregate {
        /**
         * @var DocumentAggregate $record
         */
        $record = self::fromStream([new Events\RecordWasCreated($id, $schema->toString())]);
        $schema->acceptDocumentTypeVisitor($visitor = new HandleDefaultValues(...array_keys($values)));

        $values = array_merge($values, $visitor->allDefaultValues());
        foreach ($values as $propertyCode => $value) {
            $record->setValue($propertyCode, $value, $strategy);
        }

        return $record;
    }

    public function getIdentity(): RecordId
    {
        return $this->id;
    }

    public function getDocumentId(): DocumentTypeId
    {
        return $this->schema->getIdentity();
    }

    public function setValue(
        string $code,
        RecordValue $value,
        StrategyToHandleValidationErrors $strategy = null
    ): void {
        $code = PropertyCode::fromString($code);
        if (! $strategy) {
            $strategy = new AlwaysThrowExceptionOnValidationErrors();
        }

        $property = $this->schema->getPropertyMetadata($code->toString());
        if (! $property->supportsType($value)) {
            throw $property->generateExceptionForNotSupportedTypeForValue(
                $code,
                $value
            );
        }

        if (! $property->supportsValue($value)) {
            throw $property->generateExceptionForNotSupportedValue($code, $value);
        }

        $errors = new ErrorList();
        $this->schema->acceptDocumentTypeVisitor(new ValidateProperty($code, $value, $errors));

        if ($errors->hasErrors()) {
            $strategy->handleFailure($errors);
        }

        if (array_key_exists($code->toString(), $this->values)) {
            $oldValue = $this->values[$code->toString()];
            if ($oldValue->toString() !== $value->toString()) {
                $this->mutate(
                    new Events\PropertyValueWasChanged(
                        $this->getIdentity(),
                        $this->getDocumentId(),
                        $code,
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
                    $code,
                    $value
                )
            );
        }

        $this->values[$code->toString()] = $property->toWriteFormat($value);
    }

    public function getValue(string $code): RecordValue
    {
        $property = $this->schema->getPropertyMetadata($code);

        return $property->toReadFormat($this->values[$code]);
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
