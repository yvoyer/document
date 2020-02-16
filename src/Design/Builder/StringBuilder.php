<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;

final class StringBuilder extends PropertyBuilder
{
    public function matchesRegex(string $pattern): self
    {
        $this->withConstraint('regex', $this->constraints()->regex($pattern));

        return $this;
    }

    public function required(): self
    {
        $this->withConstraint('required', $this->constraints()->required());

        return $this;
    }

    public function defaultValue(string $value): self
    {
        $this->withParameter(
            'default',
            $this->parameters()->defaultValue(StringValue::fromString($value))
        );

        return $this;
    }
}
