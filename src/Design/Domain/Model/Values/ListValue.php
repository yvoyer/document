<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class ListValue implements PropertyValue
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ListOptionValue[]
     */
    private $values = [];

    public function __construct(string $name, ListOptionValue ...$value)
    {
        $this->name = $name;
        $this->values = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return implode(
            ';',
            \array_map(
                function (ListOptionValue $value) {
                    return $value->getLabel();
                },
                $this->values
            )
        );
    }
}
