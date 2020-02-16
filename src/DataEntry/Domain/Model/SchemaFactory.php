<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentId;

interface SchemaFactory
{
    /**
     * @param DocumentId $documentId
     *
     * @return SchemaMetadata
     */
    public function createSchema(DocumentId $documentId): SchemaMetadata;
}
