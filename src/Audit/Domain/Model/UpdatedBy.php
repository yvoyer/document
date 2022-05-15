<?php declare(strict_types=1);

namespace Star\Component\Document\Audit\Domain\Model;

use Star\Component\DomainEvent\Serialization\SerializableAttribute;

interface UpdatedBy extends SerializableAttribute
{
    public function toString(): string;
}
