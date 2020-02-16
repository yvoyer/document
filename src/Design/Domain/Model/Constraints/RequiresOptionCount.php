<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class RequiresOptionCount implements PropertyConstraint
{
    /**
     * @var int
     */
    private $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if (\count($value) !== $this->count) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf(
                    'Property named "%s" allows only "%s" option(s), "%s" given.',
                    $propertyName,
                    $this->count,
                    $value->toTypedString()
                )
            );
        }
    }

    public function getName(): string
    {
        return 'required-count';
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class, ['count' => $this->count]);
    }

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        return new self($data->getArgument('count'));
    }
}
