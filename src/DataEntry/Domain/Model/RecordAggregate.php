<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Exception\UndefinedProperty;
use Star\Component\Document\DataEntry\Domain\Model\Events;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\Design\Domain\Model\DocumentSchema;
use Star\Component\DomainEvent\AggregateRoot;

final class RecordAggregate extends AggregateRoot implements DocumentRecord
{
    /**
     * @var RecordId
     */
    private $id;

    /**
     * @var string
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

    public static function withValues(
        RecordId $id,
        DocumentSchema $schema,
        array $values
    ): self {
        $record = self::withoutValues($id, $schema);
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
        return $this->getSchema()->getIdentity();
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
        $type = $this->getSchema()->getPropertyType($propertyName);
        $errors = $type->validateRawValue($propertyName, $rawValue);

        if ($errors->hasErrors()) {
            $strategy->handleFailure($errors);
        }

        $this->values[$propertyName] = $type->createValue($propertyName, $rawValue);
    }

    public function getValue(string $propertyName): RecordValue
    {
        if (! $this->hasProperty($propertyName)) {
            throw new UndefinedProperty($propertyName);
        }

        return $this->values[$propertyName];
    }

    protected function onRecordCreated(Events\RecordCreated $event): void
    {
        $this->id = $event->recordId();
        $this->schema = $event->schema();
    }

    private function hasProperty(string $propertyName): bool
    {
        return isset($this->values[$propertyName]);
    }

    private function getSchema(): DocumentSchema
    {
        return DocumentSchema::fromString($this->schema);
    }
}
