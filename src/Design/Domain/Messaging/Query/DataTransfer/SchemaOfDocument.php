<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer;

use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Schema\ReadOnlySchema;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use function array_keys;

final class SchemaOfDocument implements ReadOnlySchema
{
    private DocumentId $documentId;
    private DocumentName $documentName;
    private DocumentSchema $schema;

    public function __construct(
        DocumentId $documentId,
        DocumentName $documentName,
        DocumentSchema $schema
    ) {
        $this->documentId = $documentId;
        $this->documentName = $documentName;
        $this->schema = $schema;
    }

    public function getDocumentId(): string
    {
        return $this->documentId->toString();
    }

    public function getName(): string
    {
        return $this->documentName->toSerializableString();
    }

    /**
     * @return string[]
     */
    public function getPublicProperties(): array
    {
        $this->schema->acceptDocumentVisitor($visitor = new PropertyExtractor());

        return array_keys($visitor->properties());
    }

    public function getPublicProperty(string $name): PropertyMetadata
    {
        return $this->schema->getPropertyMetadata($name);
    }
}