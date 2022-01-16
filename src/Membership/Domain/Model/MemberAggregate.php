<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model;

use DateTimeInterface;
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
        DateTimeInterface $registeredAt
    ): self
    {
        return self::fromStream(
            [
                new MemberWasRegistered($id, $username, $registeredAt),
            ]
        );
    }
}
