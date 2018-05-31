<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

final class AlwaysReturnSchema implements SchemaFactory
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @param DocumentSchema $schema
     */
    public function __construct(DocumentSchema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param DocumentId $documentId
     *
     * @return DocumentSchema
     */
    public function createSchema(DocumentId $documentId): DocumentSchema
    {
        return $this->schema;
    }
}
