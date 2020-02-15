<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class NumberFormat implements PropertyConstraint
{
    /**
     * @var int
     */
    private $decimal;

    /**
     * @var string
     */
    private $decimalPoint;

    /**
     * @var string
     */
    private $thousandSeparator;

    public function __construct(
        int $decimal = 2,
        string $decimalPoint = '.',
        string $thousandSeparator = ','
    ) {
        $this->decimal = $decimal;
        $this->decimalPoint = $decimalPoint;
        $this->thousandSeparator = $thousandSeparator;
    }

    public function getName(): string
    {
        return 'number-format';
    }

    /**
     * @param string $propertyName
     * @param RecordValue $value
     * @param ErrorList $errors
     */
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        $expected = \number_format(
            \floatval($value->toString()),
            $this->decimal,
            $this->decimalPoint,
            $this->thousandSeparator
        );

        if ($expected !== $value->toString()) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf(
                    'Property "%s" expects a number of format "%s", "%s" given.',
                    $propertyName,
                    \sprintf(
                        'TTT%sCCC%s%s',
                        $this->thousandSeparator,
                        $this->decimalPoint,
                        \str_repeat('D', $this->decimal)
                    ),
                    $value->toTypedString()
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(
            self::class,
            [
                'decimal' => $this->decimal,
                'point' => $this->decimalPoint,
                'thousands_separator' => $this->thousandSeparator,
            ]
        );
    }

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
