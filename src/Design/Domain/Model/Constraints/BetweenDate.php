<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class BetweenDate implements PropertyConstraint
{
    private string $fromDate;
    private string $toDate;

    public function __construct(string $start, string $end)
    {
        $this->fromDate = $start;
        $this->toDate = $end;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        $constraint = new All(
            new AfterDate($this->fromDate),
            new BeforeDate($this->toDate)
        );
        $constraint->validate($propertyName, $value, $errors);
    }

    public function toData(): ConstraintData
    {
        return ConstraintData::fromConstraint(
            $this,
            [
                'from' => $this->fromDate,
                'to' => $this->toDate,
            ]
        );
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new self(
            $data->getStringArgument('from'),
            $data->getStringArgument('to')
        );
    }
}
