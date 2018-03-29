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
     * @param PropertyName $name
     * @param ValueDefinition $definition
     */
    public function visitProperty(PropertyName $name, ValueDefinition $definition);
}
