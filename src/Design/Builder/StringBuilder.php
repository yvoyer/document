<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

final class StringBuilder extends PropertyBuilder
{
    public function matchesRegex(string $pattern): self
    {
        $this->withConstraint($this->constraints()->regex($pattern));

        return $this;
    }

    public function required(): self
    {
        $this->withConstraint($this->constraints()->required());

        return $this;
    }
}
