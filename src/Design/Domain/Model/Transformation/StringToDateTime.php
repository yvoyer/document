<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;

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
