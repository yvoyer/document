<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ValueDefinition;

final class ReadDocumentStructure implements DocumentVisitor
{
    /**
     * @var array
     */
    private $parts = [];

    public function toString(): string
    {
        return $this->parts['id'];
    }

    /**
     * @param DocumentId $id
     */
    public function visitDocument(DocumentId $id)
    {
        $this->parts['id'] = $id->toString();
    }

    /**
     * @param PropertyName $name
     * @param ValueDefinition $definition
     */
    public function visitProperty(PropertyName $name, ValueDefinition $definition)
    {
        $this->parts[] = $name->toString();
    }
}
