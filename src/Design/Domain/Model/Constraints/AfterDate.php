<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class AfterDate implements PropertyConstraint
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
     * @param PropertyName $name
     * @param \DateTimeInterface $value
     * @param ErrorList $errors
     */
    public function validate(PropertyName $name, $value, ErrorList $errors): void
    {
        Assertion::isInstanceOf($value, \DateTimeInterface::class);
        if ((int) $this->target->diff($value)->format('%r%a') <= 0) {
            $errors->addError(
                $name->toString(),
                'en',
                \sprintf(
                    'The property "%s" only accepts date after "%s", "%s" given.',
                    $name->toString(),
                    $this->target->format('Y-m-d'),
                    $value->format('Y-m-d')
                )
            );
        }
    }
}
