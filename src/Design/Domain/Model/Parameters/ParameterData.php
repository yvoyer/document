<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ParameterData
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
    private function __construct(string $class, array $arguments = [])
    {
        Assertion::subclassOf($class, PropertyParameter::class);
        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function createParameter(): PropertyParameter
    {
        /**
         * @var PropertyParameter $class
         */
        $class = $this->class;

        return $class::fromParameterData($this);
    }

    /**
     * @param string $argument
     * @return mixed
     */
    public function getArgument(string $argument)
    {
        Assertion::keyExists(
            $this->arguments,
            $argument,
            'Argument "%s" could not be found.'
        );

        return $this->arguments[$argument];
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'class' => $this->class,
            'arguments' => $this->arguments,
        ];
    }

    /**
     * @param mixed[] $data
     * @return ParameterData
     */
    public static function fromArray(array $data): self
    {
        Assertion::keyExists(
            $data,
            'class',
            'Class index is not defined to a "PropertyParameter" subclass.'
        );
        Assertion::keyExists($data, 'arguments', 'Argument list is not defined to an array.');

        return new self(
            $data['class'],
            $data['arguments']
        );
    }

    /**
     * @param PropertyParameter $parameter
     * @param mixed[] $arguments
     * @return ParameterData
     */
    public static function fromParameter(PropertyParameter $parameter, array $arguments = []): self
    {
        return new self(\get_class($parameter), $arguments);
    }
}
