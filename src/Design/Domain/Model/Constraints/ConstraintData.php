<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ConstraintData
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed[]
     */
    private $arguments = [];

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

    public function createConstraint(): PropertyConstraint
    {
        /**
         * @var PropertyConstraint $class
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
        return (string) \json_encode($this->toArray());
    }

    /**
     * @param mixed[] $data
     * @return ConstraintData
     */
    public static function fromArray(array $data): self
    {
        return new self($data['class'], $data['arguments']);
    }
}
