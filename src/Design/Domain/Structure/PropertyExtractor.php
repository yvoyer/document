<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use function array_values;

final class PropertyExtractor implements DocumentVisitor
{
    /**
     * @var PropertyDefinition[] Indexed by name
     */
    private $properties = [];

    /**
     * @var DocumentConstraint[] Indexed by name
     */
    private $documentConstraints;

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function hasPropertyConstraint(string $propertyName, string $constraintName): bool
    {
        if (! $this->hasProperty($propertyName)) {
            return false;
        }

        return $this->getProperty($propertyName)->hasConstraint($constraintName);
    }

    public function hasPropertyParameter(string $propertyName, string $name): bool
    {
        if (! $this->hasProperty($propertyName)) {
            return false;
        }

        return $this->getProperty($propertyName)->hasParameter($name);
    }

    public function hasDocumentConstraint(string $name): bool
    {
        return isset($this->documentConstraints[$name]);
    }

    public function getDocumentConstraint(string $name): DocumentConstraint
    {
        return $this->documentConstraints[$name];
    }

    public function getProperty(string $name): PropertyDefinition
    {
        if (! $this->hasProperty($name)) {
            throw new ReferencePropertyNotFound(PropertyName::fromString($name));
        }

        return $this->properties[$name];
    }

    /**
     * @return PropertyDefinition[]
     */
    public function properties(): array
    {
        return array_values($this->properties);
    }

    /**
     * @return DocumentConstraint[]
     */
    public function documentConstraints(): array
    {
        return array_values($this->documentConstraints);
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        $this->documentConstraints[$name] = $constraint;
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->properties[$name->toString()] = new PropertyDefinition($name, $type);

        return false;
    }

    public function enterPropertyConstraints(PropertyName $propertyName): void
    {
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->properties[$propertyName->toString()] = $this
            ->getProperty($propertyName->toString())
            ->addConstraint($constraintName, $constraint);
    }

    public function enterPropertyParameters(PropertyName $propertyName): void
    {
    }

    public function visitPropertyParameter(
        PropertyName $propertyName,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->properties[$propertyName->toString()] = $this
            ->getProperty($propertyName->toString())
            ->addParameter($parameterName, $parameter);
    }
}
