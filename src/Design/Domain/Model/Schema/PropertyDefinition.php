<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstrainNotFound;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;

final class PropertyDefinition
{
    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var PropertyType
     */
    private $type;

    /**
     * @var PropertyConstraint[]
     */
    private $constraints = [];

    public function __construct(PropertyName $name, PropertyType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): PropertyName
    {
        return $this->name;
    }

    public function getType(): PropertyType
    {
        return $this->type;
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        if ($visitor->visitProperty($this->name, $this->type)) {
            return;
        }

        foreach ($this->constraints as $name => $constraint) {
            $visitor->visitPropertyConstraint($this->name, $name, $constraint);
        }
    }

    public function addConstraint(string $name, PropertyConstraint $constraint): PropertyDefinition
    {
        $new = $this->merge($this);
        $new->constraints[$name] = $constraint;

        return $new;
    }

    public function removeConstraint(string $name): PropertyDefinition
    {
        $new = new self($this->getName(), $this->getType());
        unset($new->constraints[$name]);

        return $new;
    }

    public function hasConstraint(string $name): bool
    {
        return isset($this->constraints[$name]);
    }

    public function getConstraint(string $name): PropertyConstraint
    {
        if (! $this->hasConstraint($name)) {
            throw new PropertyConstrainNotFound('The property "%s" do not have a constraint named "%s".');
        }

        return $this->constraints[$name];
    }

    /**
     * @return string[]
     */
    public function getConstraints(): array
    {
        return \array_keys($this->constraints);
    }

    public function validateValue(RecordValue $value, ErrorList $errors): void
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($this->name->toString(), $value, $errors);
        }
    }

    /**
     * Merge two definition into a new one, without affecting the callee and the argument.
     *
     * Note: The name and type of the new definition will be the same as the callee, while other
     * options will be merged.
     *
     * @param PropertyDefinition $definition
     *
     * @return PropertyDefinition
     */
    public function merge(PropertyDefinition $definition): PropertyDefinition
    {
        $new = new self($this->name, $this->type);
        $new->constraints = array_merge($this->constraints, $definition->constraints);

        return $new;
    }
}