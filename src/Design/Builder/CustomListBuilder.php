<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Parameters;

final class CustomListBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->requiresOptions(1);

        return $this;
    }

    public function requiresOptions(int $count): self
    {
        $this->withConstraint($this->constraints()->requiresOptionCount($count));

        return $this;
    }

    public function allowMultiOption(): self
    {
        $this->withParameter(new Parameters\AllowMultipleOptions());

        return $this;
    }
}
