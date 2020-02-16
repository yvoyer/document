<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ArrayParameter implements PropertyParameter
{
    /**
     * @var PropertyParameter[]
     */
    private $parameters = [];

    public function __construct(PropertyParameter ...$parameters)
    {
        $this->parameters = $parameters;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        foreach ($this->parameters as $parameter) {
            $parameter->validate($propertyName, $value, $errors);
        }
    }

    public function toParameterData(): ParameterData
    {
        $arguments = [];
        foreach ($this->parameters as $parameter) {
            $arguments[] = $parameter->toParameterData()->toArray();
        }

        return ParameterData::fromParameter($this, $arguments);
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        foreach ($this->parameters as $parameter) {
            $value = $parameter->toWriteFormat($value);
        }

        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        foreach ($this->parameters as $parameter) {
            $value = $parameter->toReadFormat($value);
        }

        return $value;
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self(
            ...\array_map(
                function (array $data): PropertyParameter {
                    return ParameterData::fromArray($data)->createParameter();
                },
                $data->toArray()['arguments']
            )
        );
    }
}
