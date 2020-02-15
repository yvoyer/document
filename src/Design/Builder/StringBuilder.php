<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints;

final class StringBuilder extends PropertyBuilder
{
    public function matchesRegex(string $pattern): self
    {
        $this->withConstraint(new Constraints\Regex($pattern));

        return $this;
    }

    public function required(): self
    {
        $this->withConstraint(new Constraints\RequiresValue());

        return $this;
    }
}
