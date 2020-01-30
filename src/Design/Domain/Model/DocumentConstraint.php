<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentConstraint
{
    public function onPublish(DocumentDesigner $document): void;
}
