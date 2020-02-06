<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Types\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class DateTimeToString implements ValueTransformer
{
    /**
     * @var string
     */
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function transform($rawValue): RecordValue
    {
        if ($rawValue instanceof \DateTimeInterface) {
            return DateValue::fromString($rawValue->format($this->format));
        }

        if ($rawValue === '' || $rawValue === null) {
            return new EmptyValue();
        }

        throw new \InvalidArgumentException(
            \sprintf(
                'Raw value "%s" is not a supported type, supported types: "%s".',
                $rawValue,
                \implode(
                    ', ',
                    [
                        '\DataTimeInterface',
                        'null',
                        'empty string',
                    ]
                )
            )
        );
    }
}
