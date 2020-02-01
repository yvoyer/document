<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

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

    /**
     * @param mixed $rawValue
     *
     * @return string
     */
    public function transform($rawValue): string
    {
        if ($rawValue instanceof \DateTimeInterface) {
            return $rawValue->format($this->format);
        }

        if ($rawValue === '' || $rawValue === null) {
            return (string) $rawValue;
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
