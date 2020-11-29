<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Behavior\State;

use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordAction;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use function sprintf;

final class TransitStateRecord implements RecordAction
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $transition;

    public function __construct(string $property, string $transition)
    {
        $this->property = $property;
        $this->transition = $transition;
    }

    public function toHumanReadable(): string
    {
        return sprintf('Performing transition "%s" on property "%s".', $this->transition, $this->property);
    }

    public function perform(SchemaMetadata $schema, DocumentRecord $record): void
    {
        $metadata = $schema->getPropertyMetadata($this->property);

        $record->setValue(
            $this->property,
            $metadata->doBehavior(StringValue::fromString($this->transition)),
            new AlwaysThrowExceptionOnValidationErrors()
        );
    }
}
