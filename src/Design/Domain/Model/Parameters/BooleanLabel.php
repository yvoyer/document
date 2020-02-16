<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class BooleanLabel implements PropertyParameter
{
    /**
     * @var string
     */
    private $trueLabel;

    /**
     * @var string
     */
    private $falseLabel;

    public function __construct(string $trueLabel, string $falseLabel)
    {
        $this->trueLabel = $trueLabel;
        $this->falseLabel = $falseLabel;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
    }

    public function toParameterData(): ParameterData
    {
        return ParameterData::fromParameter(
            $this,
            [
                'true-label' => $this->trueLabel,
                'false-label' => $this->falseLabel,
            ]
        );
    }

    public function getName(): string
    {
        return 'label';
    }

    public function onCreateDefaultValue(RecordValue $value): RecordValue
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self(
            $data->getArgument('true-label'),
            $data->getArgument('false-label')
        );
    }
}
