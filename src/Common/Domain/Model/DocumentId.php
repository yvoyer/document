<?php declare(strict_types=1);

namespace Star\Component\Document\Common\Domain\Model;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Identity\Identity;

// todo move to Design only, make another class for DataEntry BC
final class DocumentId implements Identity
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
     * @param DocumentId $id
     *
     * @return bool
     */
    public function matchIdentity(DocumentId $id): bool
    {
        return $id->toString() === $this->toString();
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return DocumentDesigner::class;
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
