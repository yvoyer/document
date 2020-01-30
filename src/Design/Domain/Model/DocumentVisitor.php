<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

interface DocumentVisitor
{
    public function visitDocument(DocumentId $id): void;

    public function visitProperty(PropertyDefinition $definition): void;

    /**
     * @param DocumentProperty[] $properties
     */
    public function visitEnded(array $properties): void;
}
