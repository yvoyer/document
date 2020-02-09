<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class SchemaBuilder
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    public function __construct(DocumentId $id)
    {
        $this->schema = new DocumentSchema($id);
    }

    public function addText(string $name): TextBuilder
    {
        $this->schema->addProperty($name, new StringType());

        return new TextBuilder($name, $this->schema, $this);
    }

    public function getSchema(): DocumentSchema
    {
        return $this->schema;
    }

    public static function create(DocumentId $documentId = null): self
    {
        if (!$documentId) {
            $documentId = DocumentId::random();
        }

        return new self($documentId);
    }
}
