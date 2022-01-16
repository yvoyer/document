<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentOwner
{
    public function toString(): string;
}
