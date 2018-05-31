<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

final class PropertyDefinition
{
    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $mandatory = false;

    /**
     * @param PropertyName $name The property name
     * @param string $type The property type
     */
    public function __construct(PropertyName $name, string $type)
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
     *
     * @return PropertyDefinition
     */
    public static function textDefinition(string $name): self
    {
        return new self(new PropertyName($name), 'text');
    }
}
