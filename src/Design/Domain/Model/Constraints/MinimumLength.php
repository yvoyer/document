<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class MinimumLength implements PropertyConstraint
{
    /**
     * @var int
     */
    private $length;

    private function __construct(int $length)
    {
        $this->length = $length;
    }

    public function validate(PropertyName $name, $value, ErrorList $errors): void
    {
        Assertion::string($value);
        if (\mb_strlen($value) < $this->length) {
            $errors->addError(
                $name->toString(),
                'en',
                \sprintf(
                    'Property "%s" is too short, expected a minimum of %s characters, "%s" given.',
                    $name->toString(),
                    $this->length,
                    $value
                )
            );
        }
    }

    /**
     * @param int|string $length
     * @return MinimumLength
     */
    public static function fromMixed($length): self
    {
        if (\is_string($length)) {
            return self::fromString($length);
        }

        return self::fromInt($length);
    }

    public static function fromString(string $length): self
    {
        Assertion::integerish($length);
        return self::fromInt((int) $length);
    }

    public static function fromInt(int $length): self
    {
        return new self($length);
    }
}