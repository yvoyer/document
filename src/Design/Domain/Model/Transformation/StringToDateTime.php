<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Types\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class StringToDateTime implements ValueTransformer
{
    public function transform($rawValue): RecordValue
    {
        if (empty($rawValue)) {
            return new EmptyValue();
        }

        return DateValue::fromString($rawValue);
    }
}
