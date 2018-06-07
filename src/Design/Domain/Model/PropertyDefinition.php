<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyConstraint;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyType;

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

    /**
     * @param string $name The property name
     * @param PropertyType $type The property type
     */
    public function __construct(string $name, PropertyType $type)
    {
        $this->name = new PropertyName($name);
        $this->type = $type;
    }

    /**
     * @return PropertyName
     */
    public function getName(): PropertyName
    {
        return $this->name;
    }

    /**
     * @return PropertyType
     */
    public function getType(): PropertyType
    {
        return $this->type;
    }

    /**
     * @param string $name
     * @param PropertyConstraint $constraint
     *
     * @return PropertyDefinition
     */
    public function addConstraint(string $name, PropertyConstraint $constraint): PropertyDefinition
    {
        $new = $this->merge($this);
        $new->constraints[$name] = $constraint;

        return $new;
    }

    /**
     * @param string $name
     *
     * @return PropertyDefinition
     */
    public function removeConstraint(string $name): PropertyDefinition
    {
        $new = new self($this->getName()->toString(), $this->getType());
        unset($new->constraints[$name]);

        return $new;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasConstraint(string $name): bool
    {
        return isset($this->constraints[$name]);
    }

    /**
     * @param string $name
     *
     * @return PropertyConstraint
     */
    public function getConstraint(string $name): PropertyConstraint
    {
        if (! $this->hasConstraint($name)) {
            throw new InvalidPropertyConstraint('The property "%s" do not have a constraint named "%s".');
        }

        return $this->constraints[$name];
    }

    /**
     * @param mixed $rawValue
     */
    public function validateRawValue($rawValue)
    {
        foreach ($this->constraints as $constraint) {
            // todo should allow to use different sttrategy for error handling
            $constraint->validate($this, $rawValue);
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
        $new = new self($this->name->toString(), $this->type);
        $new->constraints = array_merge($this->constraints, $definition->constraints);

        return $new;
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return PropertyDefinition
     */
    public static function fromString(string $name, string $type): self
    {
        /**
         * @var PropertyType $type
         */
        if (! class_exists($type) && ! is_a($type, PropertyType::class)) {
            throw new InvalidPropertyType($type);
        }

        return new self($name, new $type());
    }
}
