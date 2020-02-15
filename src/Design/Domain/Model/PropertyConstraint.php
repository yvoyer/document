<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;

interface PropertyConstraint
{
    /**
     * @param string $propertyName
     * @param RecordValue $value
     * @param ErrorList $errors
     */
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void;

    public function getName(): string;

    public function toData(): ConstraintData;

    public static function fromData(ConstraintData $data): PropertyConstraint;
}
