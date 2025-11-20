<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class NoConstraint implements PropertyConstraint, DocumentConstraint
{
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
    }

    public function toData(): ConstraintData
    {
        return ConstraintData::fromConstraint($this, []);
    }

    public function onRegistered(DocumentDesigner $document): void
    {
        // do nothing
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new self();
    }
}
