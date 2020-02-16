<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class PropertyExtractor implements DocumentVisitor, \Countable
{
    /**
     * @var PropertyType[]
     */
    private $properties = [];

    public function count(): int
    {
        return \count($this->properties);
    }

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->properties[$name->toString()] = $type;

        return false;
    }

    public function enterConstraints(PropertyName $propertyName): void
    {
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
    }

    public function enterParameters(PropertyName $propertyName): void
    {
    }

    public function visitParameter(PropertyName $propertyName, PropertyParameter $parameter): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
