<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class All implements PropertyConstraint
{
    /**
     * @var PropertyConstraint[]
     */
    private $constraints;

    public function __construct(PropertyConstraint $first, PropertyConstraint ...$constraints)
    {
        $this->constraints = \array_merge([$first], $constraints);
    }

    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($name, $value, $errors);
        }
    }
}
