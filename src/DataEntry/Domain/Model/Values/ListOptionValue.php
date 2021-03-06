<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;

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
        Assertion::greaterThan($id, 0);
        Assertion::notEmpty($value);
        Assertion::notEmpty($label);

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

    /**
     * @return int[]|string[]
     */
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

    /**
     * @param mixed[] $data
     * @return ListOptionValue
     */
    public static function fromArray(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'value');
        Assertion::keyExists($data, 'label');

        return new self(
            $data['id'],
            $data['value'],
            $data['label']
        );
    }
}
