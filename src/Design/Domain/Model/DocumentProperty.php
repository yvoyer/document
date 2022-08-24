<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;

final class DocumentProperty implements ReadOnlyProperty
{
    private PropertyDefinition $definition;

    public function __construct(PropertyDefinition $definition)
    {
        $this->definition = $definition;
    }

    public function addConstraint(string $name, PropertyConstraint $constraint): void
    {
        $this->definition = $this->definition->addConstraint($name, $constraint);
    }

    public function removeConstraint(string $name): void
    {
        $this->definition = $this->definition->removeConstraint($name);
    }

    public function acceptDocumentVisitor(DocumentTypeVisitor $visitor): void
    {
        $this->definition->acceptDocumentVisitor($visitor);
    }

    public function matchCode(PropertyCode $code): bool
    {
        return $code->matchCode($this->definition->getCode());
    }

    public function getDefinition(): PropertyDefinition
    {
        return $this->definition;
    }
}
