<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ConstraintBuilder
{
    public function required(): PropertyConstraint
    {
        return new Constraints\RequiresValue();
    }

    public function singleOption(): PropertyConstraint
    {
        return new Constraints\RequiresSingleOption();
    }

    /**
     * @param int|string $length
     * @return PropertyConstraint
     */
    public function minimumLength($length): PropertyConstraint
    {
        return Constraints\MinimumLength::fromMixed($length);
    }

    /**
     * @param int|string $length
     * @return PropertyConstraint
     */
    public function maximumLength($length): PropertyConstraint
    {
        return Constraints\MaximumLength::fromMixed($length);
    }

    public function regex(string $pattern): PropertyConstraint
    {
        return new Constraints\Regex($pattern);
    }

    /**
     * @param string $name
     * @param mixed[] $values
     * @return PropertyConstraint
     */
    public function fromString(string $name, array $values): PropertyConstraint
    {
        $method = \str_replace(' ', '', \lcfirst(\ucwords(\str_replace('-', ' ', $name))));
        Assertion::methodExists($method, $this, 'Constraint "%s" is not supported by the constraint builder.');

        return $this->{$method}(...$values);
    }
}
