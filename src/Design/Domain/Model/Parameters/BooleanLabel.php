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

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
//        var_dump($value);
//        if ($value->isEmpty()) {
//            return BooleanValue::falseValue();
//        }
//
        return $value;
    }

    public function validate(string $name, RecordValue $value, ErrorList $errors): void
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

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self(
            $data->getArgument('true-label'),
            $data->getArgument('false-label')
        );
    }
}
