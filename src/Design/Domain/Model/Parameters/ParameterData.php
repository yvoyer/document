<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

final class ParameterData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed[]
     */
    private $arguments = [];

    public function __construct(
        string $name,
        string $class,
        array $arguments = []
    ) {
        $this->name = $name;
        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'class' => $this->class,
            'arguments' => $this->arguments,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['class'],
            $data['arguments']
        );
    }
}
