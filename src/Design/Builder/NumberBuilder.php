<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints\NumberFormat;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;

final class NumberBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', new RequiresValue());

        return $this;
    }

    public function asFloat(int $decimal = 2, string $point = '.', string $thousandsSeparator = ','): self
    {
        $this->withConstraint('float', new NumberFormat($decimal, $point, $thousandsSeparator));

        return $this;
    }
}
