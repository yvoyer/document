<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentDesigner extends ReadOnlyDocument
{
    /**
     * Publish the document
     */
    public function publish();

    /**
     * @param PropertyDefinition $definition
     */
    public function createProperty(PropertyDefinition $definition);

    /**
     * @param PropertyName $name
     * @param string $constraintName
     * @param PropertyConstraint $constraint
     */
    public function addConstraint(PropertyName $name, string $constraintName, PropertyConstraint $constraint);

    /**
     * @param PropertyName $name
     * @param string $constraintName
     */
    public function removeConstraint(PropertyName $name, string $constraintName);
}
