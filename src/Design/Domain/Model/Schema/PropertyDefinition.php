<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
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
    private PropertyCode $code;
    private PropertyName $name;
    private PropertyType $type;

    /**
     * @var PropertyConstraint[]
     */
    private array $constraints = [];

    /**
     * @var PropertyParameter[]
     */
    private array $parameters = [];

    public function __construct(
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type
    ) {
        $this->code = $code;
        $this->name = $name;
        $this->type = $type;
    }

    final public function getCode(): PropertyCode
    {
        return $this->code;
    }

    final public function toTypedString(): string
    {
        return $this->type->toHumanReadableString();
    }

    final public function typeIs(string $type): bool
    {
        return $this->type->toHumanReadableString() === $type;
    }

    final public function acceptDocumentVisitor(DocumentTypeVisitor $visitor): void
    {
        if ($visitor->visitProperty($this->code, $this->name, $this->type)) {
            return;
        }

        $visitor->enterPropertyConstraints($this->code);
        foreach ($this->constraints as $name => $constraint) {
            $visitor->visitPropertyConstraint($this->code, $name, $constraint);
        }

        $visitor->enterPropertyParameters($this->code);
        foreach ($this->parameters as $parameterName => $parameter) {
            $visitor->visitPropertyParameter($this->code, $parameterName, $parameter);
        }
    }

    final public function addConstraint(string $name, PropertyConstraint $constraint): PropertyDefinition
    {
        $new = $this->merge($this);
        $new->constraints[$name] = $constraint;

        return $new;
    }

    final public function removeConstraint(string $name): PropertyDefinition
    {
        $new = new self($this->code, $this->name, $this->type);
        $new->constraints = $this->constraints;
        $new->parameters = $this->parameters;

        unset($new->constraints[$name]);

        return $new;
    }

    final public function hasConstraint(string $name): bool
    {
        return isset($this->constraints[$name]);
    }

    final public function getConstraint(string $name): PropertyConstraint
    {
        if (! $this->hasConstraint($name)) {
            throw new PropertyConstrainNotFound('The property "%s" do not have a constraint named "%s".');
        }

        return $this->constraints[$name];
    }

    /**
     * @return string[]
     */
    final public function getConstraints(): array
    {
        return array_keys($this->constraints);
    }

    final public function addParameter(string $parameterName, PropertyParameter $parameter): PropertyDefinition
    {
        $new = $this->merge(new PropertyDefinition($this->code, $this->name, $this->type));
        $new->parameters[$parameterName] = $parameter;

        return $new;
    }

    final public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    final public function toWriteFormat(RecordValue $value): RecordValue
    {
        $value = $this->type->toWriteFormat($value);
        foreach ($this->parameters as $parameterName => $parameter) {
            $value = $parameter->toWriteFormat($value);
        }

        return $value;
    }

    final public function toReadFormat(RecordValue $value): RecordValue
    {
        $value = $this->type->toReadFormat($value);
        foreach ($this->parameters as $parameterName => $parameter) {
            $value = $parameter->toReadFormat($value);
        }

        return $value;
    }

    final public function doBehavior(RecordValue $value): RecordValue
    {
        return $this->type->doBehavior($this->name->toString(), $value);
    }

    final public function supportsType(RecordValue $value): bool
    {
        return $this->type->supportsType($value);
    }

    final public function supportsValue(RecordValue $value): bool
    {
        return $this->type->supportsValue($value);
    }

    final public function generateExceptionForNotSupportedTypeForValue(
        PropertyCode $property,
        RecordValue $value
    ): NotSupportedTypeForValue {
        return $this->type->generateExceptionForNotSupportedTypeForValue($property, $value);
    }

    final public function generateExceptionForNotSupportedValue(
        PropertyCode $property,
        RecordValue $value
    ): InvalidPropertyValue {
        return $this->type->generateExceptionForNotSupportedValue($property, $value);
    }

    final public function validateValue(RecordValue $value, ErrorList $errors): void
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
    final public function merge(PropertyDefinition $definition): PropertyDefinition
    {
        $new = new self($this->code, $this->name, $this->type);
        $new->constraints = array_merge($this->constraints, $definition->constraints);
        $new->parameters = array_merge($this->parameters, $definition->parameters);

        return $new;
    }

    final public static function fromStrings(
        string $code,
        string $name,
        string $locale,
        PropertyType $type
    ): self {
        return new self(
            PropertyCode::fromString($code),
            PropertyName::fromLocalizedString($name, $locale),
            $type
        );
    }
}
