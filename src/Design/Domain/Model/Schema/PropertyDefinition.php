<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstrainNotFound;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\Types\NotSupportedTypeForValue;
use function array_key_exists;
use function array_keys;
use function array_merge;

final class PropertyDefinition implements PropertyMetadata
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

    /**
     * @var PropertyParameter[]
     */
    private $parameters = [];

    public function __construct(PropertyName $name, PropertyType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): PropertyName
    {
        return $this->name;
    }

    public function toTypedString(): string
    {
        return $this->type->toHumanReadableString();
    }

    public function typeIs(string $type): bool
    {
        return $this->type->toHumanReadableString() === $type;
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        if ($visitor->visitProperty($this->name, $this->type)) {
            return;
        }

        $visitor->enterPropertyConstraints($this->name);
        foreach ($this->constraints as $name => $constraint) {
            $visitor->visitPropertyConstraint($this->name, $name, $constraint);
        }

        $visitor->enterPropertyParameters($this->name);
        foreach ($this->parameters as $parameterName => $parameter) {
            $visitor->visitPropertyParameter($this->name, $parameterName, $parameter);
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
        $new = new self($this->getName(), $this->type);
        $new->constraints = $this->constraints;
        $new->parameters = $this->parameters;

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
        return array_keys($this->constraints);
    }

    public function addParameter(string $parameterName, PropertyParameter $parameter): PropertyDefinition
    {
        $new = $this->merge(new PropertyDefinition($this->name, $this->type));
        $new->parameters[$parameterName] = $parameter;

        return $new;
    }

    public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        $value = $this->type->toWriteFormat($value);
        foreach ($this->parameters as $parameterName => $parameter) {
            $value = $parameter->toWriteFormat($value);
        }

        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        $value = $this->type->toReadFormat($value);
        foreach ($this->parameters as $parameterName => $parameter) {
            $value = $parameter->toReadFormat($value);
        }

        return $value;
    }

    public function doBehavior(RecordValue $value): RecordValue
    {
        return $this->type->doBehavior($this->name->toString(), $value);
    }

    public function supportsType(RecordValue $value): bool
    {
        return $this->type->supportsType($value);
    }

    public function supportsValue(RecordValue $value): bool
    {
        return $this->type->supportsValue($value);
    }

    public function generateExceptionForNotSupportedTypeForValue(
        string $property,
        RecordValue $value
    ): NotSupportedTypeForValue {
        return $this->type->generateExceptionForNotSupportedTypeForValue($property, $value);
    }

    public function generateExceptionForNotSupportedValue(string $property, RecordValue $value): InvalidPropertyValue
    {
        return $this->type->generateExceptionForNotSupportedValue($property, $value);
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
        $new->parameters = array_merge($this->parameters, $definition->parameters);

        return $new;
    }
}
