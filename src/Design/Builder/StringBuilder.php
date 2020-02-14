<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints;

final class StringBuilder extends PropertyBuilder
{
    public function matchesRegex(string $pattern): self
    {
        $this->withConstraint('regex', new Constraints\Regex($pattern));

        return $this;
    }

    public function required(): self
    {
        $this->withConstraint('required', new Constraints\RequiresValue());

        return $this;
    }
}
