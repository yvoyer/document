<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface ReadOnlyProperty
{
    /**
     * @param PropertyName $name
     *
     * @return bool
     */
    public function matchName(PropertyName $name): bool;

    /**
     * @return PropertyDefinition
     */
    public function getDefinition(): PropertyDefinition;
}
