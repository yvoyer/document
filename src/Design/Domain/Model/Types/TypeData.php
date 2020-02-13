<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class TypeData
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed[]
     */
    private $arguments = [];

    /**
     * @param string $class
     * @param mixed[] $arguments
     */
    public function __construct(string $class, array $arguments = [])
    {
        Assertion::classExists($class);
        Assertion::implementsInterface(
            $class,
            PropertyType::class,
            'Index "class" with value "%s" was expected to implement "%s".'
        );

        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function createType(): PropertyType
    {
        /**
         * @var PropertyType $class
         */
        $class = $this->class;

        return $class::fromData($this->arguments);
    }

    /**
     * @return string[]|mixed[][]
     */
    public function toArray(): array
    {
        return [
            'class' => $this->class,
            'arguments' => $this->arguments,
        ];
    }

    public static function fromString(string $string): self
    {
        Assertion::isJsonString($string);
        $data = \json_decode($string, true);
        Assertion::keyExists($data, 'class');
        Assertion::keyExists($data, 'arguments');

        $class = $data['class'];
        Assertion::string($class);

        $arguments = $data['arguments'];
        Assertion::isArray($arguments);

        return new self($class, $arguments);
    }

    /**
     * @param mixed[] $data
     * @return TypeData
     */
    public static function fromArray(array $data): self
    {
        return self::fromString((string) \json_encode($data));
    }
}
