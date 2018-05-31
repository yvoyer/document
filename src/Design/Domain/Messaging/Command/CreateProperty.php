<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class CreateProperty implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyDefinition
     */
    private $definition;

    /**
     * @param DocumentId $documentId
     * @param PropertyDefinition $definition
     */
    public function __construct(
        DocumentId $documentId,
        PropertyDefinition $definition
    ) {
        $this->documentId = $documentId;
        $this->definition = $definition;
    }

    /**
     * @return DocumentId
     */
    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    /**
     * @return PropertyDefinition
     */
    public function definition(): PropertyDefinition
    {
        return $this->definition;
    }

    /**
     * @param string $documentId
     * @param PropertyDefinition $definition
     *
     * @return CreateProperty
     */
    public static function fromString(string $documentId, PropertyDefinition $definition): self
    {
        return new self(new DocumentId($documentId), $definition);
    }
}
