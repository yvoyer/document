<?php declare(strict_types=1);

namespace Star\Component\Document\Audit\Domain\Model;

use DateTimeInterface;
use Star\Component\DomainEvent\DomainEvent;

interface AuditableEvent extends DomainEvent
{
    public function updatedAt(): DateTimeInterface;

    /**
     * @return UpdatedBy
     */
    public function updatedBy();
}
