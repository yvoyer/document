<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

interface SchemaFactory
{
    /**
     * @param DocumentTypeId $documentId
     *
     * @return SchemaMetadata
     */
    public function createSchema(DocumentTypeId $documentId): SchemaMetadata;
}
