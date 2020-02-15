<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ClosureConstraint implements PropertyConstraint
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \Closure
     */
    private $closure;

    public function __construct(string $name, \Closure $closure)
    {
        $this->name = $name;
        $this->closure = $closure;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        $closure = $this->closure;
        $closure($propertyName, $value, $errors);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toData(): ConstraintData
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function nullConstraint(string $name = 'closure'): self
    {
        return new self($name, function () {});
    }
}
