<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Templating;

use Star\Component\Document\Design\Domain\Model\DocumentName;

final class NamedDocument implements DocumentName
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toSerializableString(): string
    {
        return $this->name;
    }
}
