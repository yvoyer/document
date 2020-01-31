<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresSingleOption;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ConstraintBuilder
{
    public function required(): PropertyConstraint
    {
        return new RequiresValue();
    }

    public function singleOption(): PropertyConstraint
    {
        return new RequiresSingleOption();
    }
}
