<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function json_decode;
use function json_encode;
use function json_last_error;

final class ConstraintData
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed[]
     */
    private $arguments;

    /**
     * @param string $class
     * @param mixed[] $arguments
     */
    public function __construct(string $class, array $arguments = [])
    {
        Assertion::implementsInterface($class, PropertyConstraint::class);
        $this->class = $class;
        $this->arguments = $arguments;
    }

    /**
     * @param string $argument
     * @return mixed
     */
    public function getArgument(string $argument)
    {
        return $this->arguments[$argument];
    }

    public function createPropertyConstraint(): PropertyConstraint
    {
        /**
         * @var PropertyConstraint $class
         */
        $class = $this->class;

        return $class::fromData($this);
    }

    public function createDocumentConstraint(): DocumentConstraint
    {
        /**
         * @var DocumentConstraint $class
         */
        $class = $this->class;

        return $class::fromData($this);
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'class' => $this->class,
            'arguments' => $this->arguments,
        ];
    }

    public function toString(): string
    {
        return (string) json_encode($this->toArray());
    }

    public static function fromString(string $string): self
    {
        Assertion::isJsonString($string);
        $data = json_decode($string, true);

        return self::fromArray($data);
    }

    /**
     * @param mixed[] $data
     * @return ConstraintData
     */
    public static function fromArray(array $data): self
    {
        Assertion::keyExists(
            $data,
            'class',
            'Constraint data does not contain an element with key "%s".'
        );
        Assertion::keyExists(
            $data,
            'arguments',
            'Constraint data does not contain an element with key "%s".'
        );

        return new self($data['class'], $data['arguments']);
    }

    public static function isValidString(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
