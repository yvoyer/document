<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

interface DesignException
{
    const INVALID_PROPERTY_VALUE = 2;
    const TOO_MANY_VALUES = 3;
    const EMPTY_REQUIRED_VALUE = 4;
    const INVALID_PROPERTY_CONSTRAINT = 6;
    const NOT_FOUND_TRANSFORMER = 7;
    const DUPLICATE_TRANNSFORMER = 8;
}
