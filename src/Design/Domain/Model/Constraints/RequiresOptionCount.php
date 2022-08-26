<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function count;
use function sprintf;

final class RequiresOptionCount implements PropertyConstraint
{
    private int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if (count($value) < $this->count) {
            $errors->addError(
                $propertyName,
                'en',
                sprintf(
                    'Property named "%s" requires at least %s option(s), "%s" given.',
                    $propertyName,
                    $this->count,
                    $value->toTypedString()
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return ConstraintData::fromConstraint($this, ['count' => $this->count]);
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new self($data->getIntegerArgument('count'));
    }
}
