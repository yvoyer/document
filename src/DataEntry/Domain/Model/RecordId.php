<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Assert\Assertion;
use Star\Component\Identity\Identity;

final class RecordId implements Identity
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
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return DocumentRecord::class;
    }

    /**
     * Returns the string value of the identity.
     *
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }
}
