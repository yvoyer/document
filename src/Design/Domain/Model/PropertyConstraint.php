<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;

interface PropertyConstraint
{
    /**
     * @param string $name
     * @param RecordValue $value
     * @param ErrorList $errors
     */
    public function validate(string $name, RecordValue $value, ErrorList $errors): void;
}
