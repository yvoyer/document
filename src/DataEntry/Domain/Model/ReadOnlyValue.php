<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

interface ReadOnlyValue
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function toString(): string;
}
