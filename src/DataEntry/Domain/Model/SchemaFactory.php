<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentSchema;

interface SchemaFactory
{
    /**
     * @param DocumentId $documentId
     *
     * @return DocumentSchema
     */
    public function createSchema(DocumentId $documentId): DocumentSchema;
}
