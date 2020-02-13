<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;

final class StringBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', new RequiresValue());

        return $this;
    }
}
