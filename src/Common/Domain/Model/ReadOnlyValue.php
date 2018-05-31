<?php declare(strict_types=1);

namespace Star\Component\Document\Common\Domain\Model;

interface ReadOnlyValue
{
    /**
     * Return the property name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string;
}
