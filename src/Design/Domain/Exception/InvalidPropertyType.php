<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

use Star\Component\Document\Design\Domain\Model\PropertyType;

final class InvalidPropertyType extends \InvalidArgumentException implements DesignException
{
    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        parent::__construct(
            sprintf(
                "The class '%s' is not a valid class implementing interface '%s'.",
                $class,
                PropertyType::class
            ),
            self::INVALID_PROPERTY_CLASS
        );
    }
}
