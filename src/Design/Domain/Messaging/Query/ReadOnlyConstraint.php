<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ReadOnlyConstraint
{
    /**
     * @var PropertyConstraint
     */
    private $constraint;

    public function __construct(PropertyConstraint $constraint)
    {
        $this->constraint = $constraint;
    }

    public function toString(): string
    {
        return $this->constraint->toData()->toString();
    }
}
