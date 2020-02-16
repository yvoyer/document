<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Assert\Assertion;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\Events;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidateConstraints;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\DomainEvent\AggregateRoot;

final class RecordAggregate extends AggregateRoot implements DocumentRecord
{
    /**
     * @var RecordId
     */
    private $id;

    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @var RecordValue[]
     */
    private $values = [];

    public static function withoutValues(
        RecordId $id,
        DocumentSchema $schema
    ): self {
        return self::fromStream([new Events\RecordCreated($id, $schema->toString())]);
    }

    /**
     * @param RecordId $id
     * @param DocumentSchema $schema
     * @param mixed[] $values
     * @return RecordAggregate
     */
    public static function withValues(
        RecordId $id,
        DocumentSchema $schema,
        array $values
    ): self {
        $record = self::withoutValues($id, $schema);
        Assertion::notEmpty(
            $values,
            'Record cannot have an empty array of values, at least one item should be given.'
        );

        foreach ($values as $property => $rawValue) {
            $record->setValue($property, $rawValue, new AlwaysThrowExceptionOnValidationErrors());
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

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @param StrategyToHandleValidationErrors $strategy
     */
    public function setValue(
        string $propertyName,
        $rawValue,
        StrategyToHandleValidationErrors $strategy
    ): void {
        $rawValue = RawValue::fromMixed($rawValue);
        $type = $this->schema->getDefinition($propertyName)->getType();
        $errors = new ErrorList();
        $value = $type->createValue($propertyName, $rawValue);
        $this->schema->acceptDocumentVisitor(new ValidateConstraints($propertyName, $value, $errors));

        if ($errors->hasErrors()) {
            $strategy->handleFailure($errors);
        }

        $this->values[$propertyName] = $value;
    }

    public function getValue(string $propertyName): RecordValue
    {
        $property = $this->schema->getDefinition($propertyName);
        if (! $this->hasValue($propertyName)) {
            $this->values[$propertyName] = $property->createDefaultValue();
        }

        return $this->values[$propertyName];
    }

    protected function onRecordCreated(Events\RecordCreated $event): void
    {
        $this->id = $event->recordId();
        $this->schema = DocumentSchema::fromString($event->schema());
    }

    private function hasValue(string $propertyName): bool
    {
        return \array_key_exists($propertyName, $this->values);
    }
}
