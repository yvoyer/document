<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ConstraintBuilder
{
    public function beforeDate(string $date): PropertyConstraint
    {
        return new Constraints\BeforeDate($date);
    }

    public function afterDate(string $date): PropertyConstraint
    {
        return new Constraints\AfterDate($date);
    }

    public function required(): PropertyConstraint
    {
        return new Constraints\RequiresValue();
    }

    public function betweenDate(string $startDate, string $endDate): PropertyConstraint
    {
        return new Constraints\BetweenDate($startDate, $endDate);
    }

    public function requiresOptionCount(int $count): PropertyConstraint
    {
        return new Constraints\RequiresOptionCount($count);
    }

    public function numberFormat(
        int $decimal = 2,
        string $decimalPoint = '.',
        string $thousandSeparator = ','
    ): PropertyConstraint {
        return new Constraints\NumberFormat(
            $decimal,
            $decimalPoint,
            $thousandSeparator
        );
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
}
