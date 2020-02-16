<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use DateTimeImmutable;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateParser;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function sprintf;

final class AfterDate implements PropertyConstraint
{
    private const FORMAT = 'Y-m-d';

    /**
     * @var \DateTimeInterface
     */
    private $target;

    public function __construct(string $target)
    {
        $this->target = new DateTimeImmutable($target);
    }

    /**
     * @param string $propertyName
     * @param RecordValue|DateValue $value
     * @param ErrorList $errors
     */
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if ($value->isEmpty()) {
            return;
        }

        $date = DateParser::fromString($value->toString());
        if (!$date->isValid() || (int) $this->target->diff($date->toDateTime())->format('%r%a') <= 0) {
            $errors->addError(
                $propertyName,
                'en',
                sprintf(
                    'The property "%s" only accepts date after "%s", "%s" given.',
                    $propertyName,
                    $this->target->format(self::FORMAT),
                    $value->toTypedString()
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class, ['target' => $this->target->format(self::FORMAT)]);
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new static($data->getArgument('target'));
    }
}
