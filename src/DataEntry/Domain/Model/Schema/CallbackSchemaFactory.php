<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\DocumentId;

final class CallbackSchemaFactory implements SchemaFactory
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function createSchema(DocumentId $documentId): SchemaMetadata
    {
        $closure = $this->callback;

        return $closure($documentId);
    }
}
