<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyAttribute;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class ChangePropertyDefinition implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var PropertyAttribute
     */
    private $attribute;

    /**
     * @param DocumentId $documentId
     * @param PropertyName $name
     * @param PropertyAttribute $attribute
     */
    public function __construct(DocumentId $documentId, PropertyName $name, PropertyAttribute $attribute)
    {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->attribute = $attribute;
    }

    /**
     * @return DocumentId
     */
    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    /**
     * @return PropertyName
     */
    public function name(): PropertyName
    {
        return $this->name;
    }

    /**
     * @return PropertyAttribute
     */
    public function attribute(): PropertyAttribute
    {
        return $this->attribute;
    }

    /**
     * @param string $documentId
     * @param string $name
     * @param PropertyAttribute $attribute
     *
     * @return ChangePropertyDefinition
     */
    public static function fromString(string $documentId, string $name, PropertyAttribute $attribute): self
    {
        return new self(
            new DocumentId($documentId),
            new PropertyName($name),
            $attribute
        );
    }
}
