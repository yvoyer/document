<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

interface ReadOnlyDocument
{
    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId;

    /**
     * @return bool
     */
    public function isPublished(): bool;

    /**
     * @param PropertyName $name
     *
     * @return PropertyDefinition
     */
    public function getPropertyDefinition(PropertyName $name): PropertyDefinition;

    /**
     * @param DocumentVisitor $visitor
     */
    public function acceptDocumentVisitor(DocumentVisitor $visitor): void;
}
