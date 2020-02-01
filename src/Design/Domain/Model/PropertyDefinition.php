<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyConstraint;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerFactory;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

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
     * @var TransformerIdentifier[]
     */
    private $transformers = [];

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
            throw new InvalidPropertyConstraint('The property "%s" do not have a constraint named "%s".');
        }

        return $this->constraints[$name];
    }

    public function addTransformer(TransformerIdentifier $identifier): PropertyDefinition
    {
        $new = new self($this->getName(), $this->getType());
        $new->transformers[$identifier->toString()] = $identifier;

        return $this->merge($new);
    }

    public function hasTransformer(TransformerIdentifier $identifier): bool
    {
        return \array_key_exists($identifier->toString(), $this->transformers);
    }

    /**
     * @param mixed $rawValue
     * @param TransformerFactory $factory
     *
     * @return mixed
     */
    public function transformValue($rawValue, TransformerFactory $factory)
    {
        foreach ($this->transformers as $id) {
            $rawValue = $factory->createTransformer($id)->transform($rawValue);
        }

        return $rawValue;
    }

    /**
     * @param mixed $rawValue
     */
    public function validateRawValue($rawValue): void
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
        $new = new self($this->name, $this->type);
        $new->constraints = array_merge($this->constraints, $definition->constraints);
        $new->transformers = array_merge($this->transformers, $definition->transformers);

        return $new;
    }
}
