<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Membership\Domain\Model\Events\MemberWasRegistered;
use Star\Component\DomainEvent\AggregateRoot;

final class MemberAggregate extends AggregateRoot
{
    private MemberId $id;

    final public function getIdentity(): MemberId
    {
        return $this->id;
    }

    protected function onMemberWasRegistered(MemberWasRegistered $event): void
    {
        $this->id = $event->memberId();
    }

    public static function registered(
        MemberId $id,
        Username $username,
        AuditDateTime $registeredAt
    ): self
    {
        return self::fromStream(
            [
                new MemberWasRegistered($id, $username, $registeredAt),
            ]
        );
    }
}
