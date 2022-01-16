<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Templating;

use Star\Component\Document\Design\Domain\Model\DocumentName;

final class NotNamedDocument implements DocumentName
{
    public function toSerializableString(): string
    {
        return 'default-name';
    }
}
