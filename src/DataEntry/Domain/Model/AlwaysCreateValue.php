<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Common\Domain\Model\PropertyValue;

final class AlwaysCreateValue implements DocumentSchema
{
    /**
     * @var PropertyValue
     */
    private $value;

    /**
     * @param PropertyValue $value
     */
    public function __construct(PropertyValue $value)
    {
        $this->value = $value;
    }

    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     *
     * @return PropertyValue
     */
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        return $this->value;
    }
}
