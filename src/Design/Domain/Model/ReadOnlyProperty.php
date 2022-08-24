<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;

interface ReadOnlyProperty
{
    /**
     * @param PropertyCode $code
     *
     * @return bool
     */
    public function matchCode(PropertyCode $code): bool;

    /**
     * @return PropertyDefinition
     */
    public function getDefinition(): PropertyDefinition;
}
