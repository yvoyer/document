<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\Username;

final class MemberWasRegistered implements MemberEvent
{
    private string $id;
    private string $username;
    private AuditDateTime $registeredAt;

    public function __construct(
        MemberId $id,
        Username $username,
        AuditDateTime $registeredAt
    ) {
        $this->id = $id->toString();
        $this->username = $username->toString();
        $this->registeredAt = $registeredAt;
    }

    final public function memberId(): MemberId
    {
        return MemberId::fromString($this->id);
    }

    final public function username(): Username
    {
        return Username::fromString($this->username);
    }

    final public function registeredAt(): AuditDateTime
    {
        return $this->registeredAt;
    }
}
