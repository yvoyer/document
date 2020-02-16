<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Parameters;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ParameterBuilder
{
    public function defaultValue(RecordValue $value): PropertyParameter
    {
        return new Parameters\DefaultValue($value);
    }

    public function labeled(string $trueLabel, string $falseLabel): PropertyParameter
    {
        return new Parameters\BooleanLabel($trueLabel, $falseLabel);
    }

    public function singleListOption(): PropertyParameter
    {
        return new Parameters\DisallowMultipleOptions();
    }

    public function dateFormat(string $format): PropertyParameter
    {
        return new Parameters\DateFormat($format);
    }
}
