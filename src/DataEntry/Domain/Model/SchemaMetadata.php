<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;

interface SchemaMetadata
{
    public function getIdentity(): DocumentTypeId;

    public function toString(): string;

    public function getPropertyMetadata(string $code): PropertyMetadata;

    public function acceptDocumentTypeVisitor(DocumentTypeVisitor $visitor): void;
}
