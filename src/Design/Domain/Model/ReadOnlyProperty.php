<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;

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
