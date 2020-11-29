<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class InvalidDefaultValue extends \InvalidArgumentException
{
    public function __construct(RecordValue $value)
    {
        parent::__construct(
            \sprintf(
                'Value "%s" is not a valid default value.',
                $value->toTypedString()
            )
        );
    }
}
