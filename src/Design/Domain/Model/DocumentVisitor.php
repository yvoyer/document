<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

interface DocumentVisitor
{
    /**
     * @param DocumentId $id
     */
    public function visitDocument(DocumentId $id);

    /**
     * @param PropertyDefinition $definition
     */
    public function visitProperty(PropertyDefinition $definition);
}
