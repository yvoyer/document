<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentType
{
    public function toString(): string;
}