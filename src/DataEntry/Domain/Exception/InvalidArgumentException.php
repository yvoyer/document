<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Exception;

final class InvalidArgumentException extends \InvalidArgumentException implements DataEntryException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(
            $message,
            self::INVALID_ARGUMENT
        );
    }
}
