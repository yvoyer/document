<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer;

use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Schema\ReadOnlySchema;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use function array_keys;

final class SchemaOfDocument implements ReadOnlySchema
{
    private DocumentTypeId $documentId;
    private DocumentTypeName $documentName;
    private DocumentSchema $schema;

    public function __construct(
        DocumentTypeId $documentId,
        DocumentTypeName $documentName,
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
        return $this->documentName->toString();
    }

    /**
     * @return string[]
     */
    public function getPublicProperties(): array
    {
        $this->schema->acceptDocumentTypeVisitor($visitor = new PropertyExtractor());

        return array_keys($visitor->properties());
    }

    public function getPublicProperty(string $code): PropertyMetadata
    {
        return $this->schema->getPropertyMetadata($code);
    }
}
