<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;

interface PropertyConstraint extends CanBeValidated
{
    public function getName(): string;

    public function toData(): ConstraintData;

    public static function fromData(ConstraintData $data): PropertyConstraint;
}
