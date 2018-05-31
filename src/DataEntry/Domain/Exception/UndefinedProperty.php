<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Exception;

final class UndefinedProperty extends \RuntimeException implements DataEntryException
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf(
                'Property with name "%s" is not defined on record.',
                $name
            ),
            self::UNDEFINED_PROPERTY
        );
    }
}
