<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function preg_match;
use function sprintf;

final class RecordValueGuesser
{
    /**
     * @var string[]
     */
    private static $supported = [
        'boolean' => BooleanValue::class,
        'integer' => IntegerValue::class,
        'string' => StringValue::class,
        'array' => ArrayOfInteger::class,
        'float' => FloatValue::class,
        'empty' => EmptyValue::class,
        'date' => DateValue::class,
    ];

    public static function guessValue(string $value): RecordValue
    {
        $result = preg_match('/(?<type>\w+)\((?<value>.*)\)/', $value, $matches);
        Assertion::greaterThan(
            $result,
            0,
            sprintf('Provided value "%s" is not of valid format, should be of format "type(value)".', $value)
        );

        $type = $matches['type'];
        Assertion::keyExists(
            self::$supported,
            $type,
            'Type of value "%s" is not mapped to a record value class.'
        );

        /**
         * @var RecordValue $class
         */
        $class = self::$supported[$type];

        return $class::fromString($matches['value']);
    }
}
