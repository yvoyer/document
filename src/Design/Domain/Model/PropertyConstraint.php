<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;

interface PropertyConstraint
{
    /**
     * @param PropertyName $name
     * @param mixed $value
     * @param ErrorList $errors
     */
    public function validate(PropertyName $name, $value, ErrorList $errors): void;
}
