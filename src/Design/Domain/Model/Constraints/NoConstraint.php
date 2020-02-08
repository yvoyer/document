<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class NoConstraint implements PropertyConstraint, DocumentConstraint
{
    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
    }

    public function onPublish(DocumentDesigner $document): void
    {
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
