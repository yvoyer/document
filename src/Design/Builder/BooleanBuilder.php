<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Parameters\BooleanLabel;
use Star\Component\Document\Design\Domain\Model\Parameters\DefaultValue;
use Star\Component\Document\Design\Domain\Model\Values\BooleanValue;

final class BooleanBuilder extends PropertyBuilder
{
    public function labeled(string $trueLabel, string $falseLabel): self
    {
        $this->withParameter(new BooleanLabel($trueLabel, $falseLabel));

        return $this;
    }

    public function defaultValue(bool $value): self
    {
        $this->withParameter(new DefaultValue(new BooleanValue($value)));

        return $this;
    }

    public function required(): self
    {
        $this->withConstraint($this->constraints()->required());

        return $this;
    }
}
