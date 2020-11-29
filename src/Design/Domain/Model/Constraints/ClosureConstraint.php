<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ClosureConstraint implements PropertyConstraint
{
    /**
     * @var \Closure
     */
    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        $closure = $this->closure;
        $closure($propertyName, $value, $errors);
    }

    public function toData(): ConstraintData
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function nullConstraint(): self
    {
        return new self(
            function () {
            }
        );
    }
}
