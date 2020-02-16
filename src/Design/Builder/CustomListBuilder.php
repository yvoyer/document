<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

final class CustomListBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', $this->constraints()->requiresOptionCount(1));

        return $this;
    }

    public function minimumOptionCount(int $count): self
    {
        $this->withConstraint('minimum-option-count', $this->constraints()->requiresOptionCount($count));

        return $this;
    }

    public function singleOption(): self
    {
        $this->withParameter('single-option', $this->parameters()->singleListOption());

        return $this;
    }
}
