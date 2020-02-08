<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

final class ListOptionValue
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $label;

    public function __construct(int $id, string $value, string $label)
    {
        $this->id = $id;
        $this->value = $value;
        $this->label = $label;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'value' => $this->getValue(),
            'label' => $this->getLabel(),
        ];
    }

    public static function withValueAsLabel(int $id, string $value): self
    {
        return new self($id, $value, $value);
    }
}
