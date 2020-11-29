<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;

interface SchemaMetadata
{
    public function getIdentity(): DocumentId;

    public function toString(): string;

    public function getPropertyMetadata(string $propertyName): PropertyMetadata;

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void;
}
