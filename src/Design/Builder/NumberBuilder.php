<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

final class NumberBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', $this->constraints()->required());

        return $this;
    }

    public function asFloat(int $decimal = 2, string $point = '.', string $thousandsSeparator = ','): self
    {
        $this->withConstraint(
            'number-format',
            $this->constraints()->numberFormat($decimal, $point, $thousandsSeparator)
        );

        return $this;
    }
}
