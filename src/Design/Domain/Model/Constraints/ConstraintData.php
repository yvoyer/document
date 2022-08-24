<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;
use function get_class;
use function json_decode;
use function json_encode;
use function serialize;

final class ConstraintData implements SerializableAttribute
{
    private string $class;
    private array $arguments;

    private function __construct(string $class, array $arguments = [])
    {
        Assertion::implementsInterface($class, PropertyConstraint::class);
        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function getArrayArgument(string $argument): array
    {
        return $this->arguments[$argument];
    }

    public function getStringArgument(string $argument): string
    {
        return $this->arguments[$argument];
    }

    public function getIntegerArgument(string $argument): int
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

    public function toSerializableString(): string
    {
        return serialize($this->toArray());
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

        return self::fromClass($data['class'], $data['arguments']);
    }

    public static function fromClass(string $class, array $arguments): self
    {
        return new self($class, $arguments);
    }

    public static function fromConstraint(PropertyConstraint $constraint, array $arguments): self
    {
        return self::fromClass(get_class($constraint), $arguments);
    }
}
