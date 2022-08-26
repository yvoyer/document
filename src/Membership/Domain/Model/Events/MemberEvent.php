<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model\Events;

use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\DomainEvent\DomainEvent;

interface MemberEvent extends DomainEvent
{
    public function memberId(): MemberId;
}
