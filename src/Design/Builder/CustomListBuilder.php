<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints;

final class CustomListBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', new Constraints\RequiresValue());

        return $this;
    }

    public function singleOption(): self
    {
        $this->withConstraint('single-option', new Constraints\RequiresSingleOption());

        return $this;
    }
}
