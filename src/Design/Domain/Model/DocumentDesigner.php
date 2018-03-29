<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentDesigner
{
    public function publish();

    /**
     * @param PropertyName $name
     * @param ValueDefinition $definition
     */
    public function createProperty(PropertyName $name, ValueDefinition $definition);
}
