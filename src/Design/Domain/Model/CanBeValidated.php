<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;

interface CanBeValidated
{
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void;
}
