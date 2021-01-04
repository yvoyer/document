<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection;

final class ColumnMetadata
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    private function __construct(string $name, string $type, $value)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function stringColumn(string $name, string $value): self
    {
        return new self($name, 'string', $value);
    }
}
