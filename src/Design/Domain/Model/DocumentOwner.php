<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Audit\Domain\Model\UpdatedBy;

interface DocumentOwner extends UpdatedBy
{
    public function toString(): string;
}
