<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use function array_values;

final class PropertyExtractor implements DocumentTypeVisitor
{
    /**
     * @var PropertyDefinition[] Indexed by code
     */
    private array $properties = [];

    /**
     * @var DocumentConstraint[] Indexed by code
     */
    private array $documentConstraints;

    public function hasProperty(PropertyCode $code): bool
    {
        return isset($this->properties[$code->toString()]);
    }

    public function hasPropertyConstraint(PropertyCode $code, string $constraintName): bool
    {
        if (! $this->hasProperty($code)) {
            return false;
        }

        return $this->getProperty($code)->hasConstraint($constraintName);
    }

    public function hasPropertyParameter(PropertyCode $code, string $name): bool
    {
        if (! $this->hasProperty($code)) {
            return false;
        }

        return $this->getProperty($code)->hasParameter($name);
    }

    public function hasDocumentConstraint(string $name): bool
    {
        return isset($this->documentConstraints[$name]);
    }

    public function getDocumentConstraint(string $name): DocumentConstraint
    {
        return $this->documentConstraints[$name];
    }

    public function getProperty(PropertyCode $code): PropertyDefinition
    {
        if (! $this->hasProperty($code)) {
            throw new ReferencePropertyNotFound($code);
        }

        return $this->properties[$code->toString()];
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

    public function visitDocumentType(DocumentTypeId $id): void
    {
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        $this->documentConstraints[$name] = $constraint;
    }

    public function visitProperty(PropertyCode $code, PropertyName $name, PropertyType $type): bool
    {
        $this->properties[$code->toString()] = new PropertyDefinition($code, $name, $type);

        return false;
    }

    public function enterPropertyConstraints(PropertyCode $code): void
    {
    }

    public function visitPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->properties[$code->toString()] = $this
            ->getProperty($code)
            ->addConstraint($constraintName, $constraint);
    }

    public function enterPropertyParameters(PropertyCode $code): void
    {
    }

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->properties[$code->toString()] = $this
            ->getProperty($code)
            ->addParameter($parameterName, $parameter);
    }
}
