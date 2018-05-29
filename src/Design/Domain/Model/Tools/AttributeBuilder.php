<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Tools;

use Star\Component\Document\Design\Domain\Model\Definition\RequiredProperty;
use Star\Component\Document\Design\Domain\Model\PropertyAttribute;

final class AttributeBuilder
{
    /**
     * @return PropertyAttribute
     */
    public function required(): PropertyAttribute
    {
        return new RequiredProperty();
    }

    /**
     * @return AttributeBuilder
     */
    public static function create(): self
    {
        return new self();
    }
}
