<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Design\Domain\Model\Constraints\RequiredValue;
use Star\Component\Document\Design\Domain\Model\Constraints\RequireSingleOption;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ConstraintBuilder
{
    /**
     * @return PropertyConstraint
     */
    public function required(): PropertyConstraint
    {
        return new RequiredValue();
    }

    /**
     * @return PropertyConstraint
     */
    public function singleOption(): PropertyConstraint
    {
        return new RequireSingleOption();
    }
}
