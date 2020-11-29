<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;

interface Constraint
{
    /**
     * @return ConstraintData
     */
    public function toData(): ConstraintData;

    /**
     * @param ConstraintData $data
     * @return static
     */
    public static function fromData(ConstraintData $data): Constraint;
}
