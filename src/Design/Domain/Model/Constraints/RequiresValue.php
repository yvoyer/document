<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class RequiresValue implements PropertyConstraint
{
    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if ($value->isEmpty()) {
            $errors->addError(
                $name,
                'en',
                \sprintf(
                    'Property named "%s" is required, but empty value given.',
                    $name
                )
            );
        }
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
