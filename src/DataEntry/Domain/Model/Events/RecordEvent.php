<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\DomainEvent\DomainEvent;

interface RecordEvent extends DomainEvent
{
    public function recordId(): RecordId;
}
