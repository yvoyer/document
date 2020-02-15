<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\Values\DateParser;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class BeforeDate implements PropertyConstraint
{
    private const FORMAT = 'Y-m-d';

    /**
     * @var \DateTimeInterface
     */
    private $target;

    public function __construct(string $target)
    {
        $this->target = new \DateTimeImmutable($target);
    }

    public function getName(): string
    {
        return 'before-date';
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
        if ($date->diff($this->target, '%r%a') >= 0) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf(
                    'The property "%s" only accepts date before "%s", "%s" given.',
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

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        return new self($data->getArgument('target'));
    }
}
