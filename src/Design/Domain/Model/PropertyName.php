<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Assert\Assertion;

final class PropertyName
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assertion::notBlank($value);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * @param PropertyName $name
     *
     * @return bool
     */
    public function matchName(PropertyName $name): bool
    {
        return $name->toString() === $this->toString();
    }
}
