<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;

final class BooleanBuilder extends PropertyBuilder
{
    public function labeled(string $trueLabel, string $falseLabel): self
    {
        $this->withParameter(
            'label',
            $this->parameters()->labeled($trueLabel, $falseLabel)
        );

        return $this;
    }

    public function defaultValue(bool $value): self
    {
        $this->withParameter(
            'default-value',
            $this->parameters()->defaultValue(BooleanValue::fromBool($value))
        );

        return $this;
    }

    public function required(): self
    {
        $this->withConstraint('required', $this->constraints()->required());

        return $this;
    }
}
