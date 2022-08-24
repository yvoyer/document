<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class AlwaysReturnSchema implements SchemaFactory
{
    /**
     * @var SchemaMetadata
     */
    private $schema;

    public function __construct(SchemaMetadata $schema)
    {
        $this->schema = $schema;
    }

    public function createSchema(DocumentTypeId $documentId): SchemaMetadata
    {
        return $this->schema;
    }
}
