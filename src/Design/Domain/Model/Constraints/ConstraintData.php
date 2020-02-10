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
     * @var array
     */
    private $arguments = [];

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
         * @var $class PropertyConstraint
         */
        $class = $this->class;

        return $class::fromData($this);
    }

    public function toArray(): array
    {
        return [
            'class' => $this->class,
            'arguments' => $this->arguments,
        ];
    }

    public function toString(): string
    {
        return \json_encode($this->toArray());
    }

    public static function fromArray(array $data): self
    {
        return new self($data['class'], $data['arguments']);
    }
}
