<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

final class InvalidPropertyValue extends \InvalidArgumentException implements DesignException
{
    /**
     * @param string $propertyName
     * @param string $type
     * @param mixed $value
     */
    public function __construct(string $propertyName, string $type, $value)
    {
        parent::__construct(
            sprintf(
                'The property "%s" expected a "%s" value, "%s" given.',
                $propertyName,
                $type,
                \gettype($value)
            ),
            self::INVALID_PROPERTY_VALUE
        );
    }
}
