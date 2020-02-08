<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentSchema;

final class AlwaysReturnSchema implements SchemaFactory
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    public function __construct(DocumentSchema $schema)
    {
        $this->schema = $schema;
    }

    public function createSchema(DocumentId $documentId): DocumentSchema
    {
        return $this->schema;
    }
}
