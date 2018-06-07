<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

interface PropertyValue extends RecordValue // todo remove RecordValue from here
{
    /**
     * Return the property name
     *
     * @return string
     */
    public function getName(): string;
}
