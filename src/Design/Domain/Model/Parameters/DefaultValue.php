<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Assert\Assertion;
use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use function get_class;

final class DefaultValue implements PropertyParameter
{
    /**
     * @var RecordValue
     */
    private $value;

    public function __construct(RecordValue $value)
    {
        if ($value->isEmpty()) {
            throw new InvalidDefaultValue($value);
        }

        $this->value = $value;
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        if ($value->isEmpty()) {
            return $this->value;
        }

        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if ($value->isEmpty() || $value instanceof BooleanValue) {
            return;
        }

        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function toParameterData(): ParameterData
    {
        return ParameterData::fromParameter(
            $this,
            [
                'value' => $this->value->toString(),
                'value-class' => get_class($this->value),
            ]
        );
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        /**
         * @var RecordValue $class
         */
        $class = $data->getArgument('value-class');
        Assertion::subclassOf($class, RecordValue::class);

        return new self($class::fromString($data->getArgument('value')));
    }
}
