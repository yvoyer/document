<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function is_string;
use function mb_strlen;
use function sprintf;

final class MaximumLength implements PropertyConstraint
{
    private int $length;

    private function __construct(int $length)
    {
        $this->length = $length;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if (mb_strlen($value->toString()) > $this->length) {
            $errors->addError(
                $propertyName,
                'en',
                sprintf(
                    'Property "%s" is too long, expected a maximum of %s characters, "%s" given.',
                    $propertyName,
                    $this->length,
                    $value->toString()
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return ConstraintData::fromConstraint($this, ['length' => $this->length]);
    }

    public static function fromInt(int $length): self
    {
        return new self($length);
    }

    public static function fromString(string $length): self
    {
        Assertion::integerish($length);
        return self::fromInt((int) $length);
    }

    /**
     * @param int|string $length
     * @return MaximumLength
     */
    public static function fromMixed($length): self
    {
        if (is_string($length)) {
            return self::fromString($length);
        }

        return self::fromInt($length);
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new self($data->getIntegerArgument('length'));
    }
}
