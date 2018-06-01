<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

final class InvalidPropertyConstraint extends \InvalidArgumentException implements DesignException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(
            $message,
            self::INVALID_PROPERTY_CONSTRAINT
        );
    }
}
