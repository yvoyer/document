<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentDesigner extends ReadOnlyDocument
{
    public function publish();

    /**
     * @param PropertyDefinition $definition
     */
    public function createProperty(PropertyDefinition $definition);

    /**
     * @param PropertyName $name
     * @param PropertyAttribute $attribute
     */
    public function changePropertyAttribute(PropertyName $name, PropertyAttribute $attribute);
}
