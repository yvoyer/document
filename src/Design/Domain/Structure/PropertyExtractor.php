<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

final class PropertyExtractor implements DocumentVisitor, \Countable
{
    /**
     * @var PropertyDefinition[]
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

    public function visitProperty(PropertyName $name, PropertyType $type): void
    {
        $this->properties[$name->toString()] = $type;
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
    }

    public function visitValueTransformer(
        PropertyName $propertyName,
        string $constraintName,
        TransformerIdentifier $identifier
    ): void {
    }
}
