<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Exception;

interface DataEntryException
{
    const UNDEFINED_PROPERTY = 1;
    const INVALID_ARGUMENT = 2;
}
