<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

final class InvalidPropertyValue extends \InvalidArgumentException implements DesignException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message, self::INVALID_PROPERTY_VALUE);
    }

    /**
     * @param string $propertyName
     * @param string $type
     * @param mixed $value
     *
     * @return InvalidPropertyValue
     */
    public static function invalidValueForType(string $propertyName, string $type, $value): self
    {
        return new self(
            sprintf(
                'The property "%s" expected a "%s" value, "%s" given.',
                $propertyName,
                $type,
                self::getValueType($value)
            )
        );
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public static function getValueType($value): string
    {
        switch (\gettype($value)) {
            case 'boolean':
                $value = ($value) ? 'true' : 'false';
                break;

            case 'array':
                $value = \json_encode($value);
                break;

            case 'object':
                $value = \get_class($value);
                break;

            case 'NULL':
                $value = 'NULL';
                break;
        }

        return (string) $value;
    }
}
