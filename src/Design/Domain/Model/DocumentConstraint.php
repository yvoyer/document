<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentConstraint extends Constraint
{
    public function onRegistered(DocumentDesigner $document): void;
}
