<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class BeforeDate implements PropertyConstraint
{
    /**
     * @var \DateTimeInterface
     */
    private $target;

    public function __construct(string $target)
    {
        $this->target = new \DateTimeImmutable($target);
    }

    /**
     * @param string $name
     * @param RecordValue|DateValue $value
     * @param ErrorList $errors
     */
    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if ($value->isEmpty()) {
            return;
        }

        Assertion::isInstanceOf($value, DateValue::class);
        if ((int) $this->target->diff($value->toDateTime())->format('%r%a') >= 0) {
            $errors->addError(
                $name,
                'en',
                \sprintf(
                    'The property "%s" only accepts date before "%s", "%s" given.',
                    $name,
                    $this->target->format('Y-m-d'),
                    $value->toString()
                )
            );
        }
    }
}
