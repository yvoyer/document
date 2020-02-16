<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ClosureParameter implements PropertyParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \Closure
     */
    private $closure;

    public function __construct(string $name, \Closure $closure)
    {
        $this->name = $name;
        $this->closure = $closure;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function toParameterData(): ParameterData
    {
        return ParameterData::fromParameter(
            $this,
            [
                'name' => $this->name,
                'closure' => $this->closure,
            ]
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function onCreateDefaultValue(RecordValue $value): RecordValue
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self(
            $data->getArgument('name'),
            $data->getArgument('closure')
        );
    }
}
