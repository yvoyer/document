<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

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
     * @var bool
     */
    private $mandatory = false;

    /**
     * @param PropertyName $name The property name
     * @param PropertyType $type The property type
     */
    public function __construct(PropertyName $name, PropertyType $type)
    {
        $this->name = $name;
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
     * Whether the property is required on data input.
     *
     * @param bool $isRequired
     */
    public function setMandatory(bool $isRequired)
    {
        $this->mandatory = $isRequired;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->mandatory;
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

        return new self(new PropertyName($name), new $type());
    }
}
