<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function array_map;
use function array_merge;

final class All implements PropertyConstraint, DocumentConstraint
{
    /**
     * @var PropertyConstraint[]
     */
    private $constraints;

    public function __construct(PropertyConstraint $first, PropertyConstraint ...$constraints)
    {
        $this->constraints = array_merge([$first], $constraints);
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($propertyName, $value, $errors);
        }
    }

    public function onRegistered(DocumentDesigner $document): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toData(): ConstraintData
    {
        return ConstraintData::fromConstraint(
            $this,
            [
                'constraints' => array_map(
                    function (PropertyConstraint $constraint) {
                        return $constraint->toData()->toArray();
                    },
                    $this->constraints
                ),
            ]
        );
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new static(
            ...array_map(
                function (array $constraint) {
                    /**
                     * @var PropertyConstraint $class
                     */
                    $class = $constraint['class'];
                    return $class::fromData(ConstraintData::fromClass($constraint['class'], $constraint['arguments']));
                },
                $data->getArrayArgument('constraints')
            )
        );
    }
}
