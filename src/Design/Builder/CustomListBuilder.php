<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\Parameters;

final class CustomListBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint(new Constraints\RequiresValue());

        return $this;
    }

    public function allowMultiOption(): self
    {
        $this->withParameter(new Parameters\AllowMultipleOptions());

        return $this;
    }
}
