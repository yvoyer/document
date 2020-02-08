<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class DocumentToSchema implements DocumentVisitor
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    public function visitDocument(DocumentId $id): void
    {
        $this->schema = new DocumentSchema($id);
    }

    public function visitProperty(PropertyDefinition $definition): void
    {
        $this->schema->addProperty(
            $definition->getName()->toString(),
            $definition->getType()
        );
    }

    public function visitEnded(array $properties): void
    {
    }

    public function getSchema(): DocumentSchema
    {
        return $this->schema;
    }
}
