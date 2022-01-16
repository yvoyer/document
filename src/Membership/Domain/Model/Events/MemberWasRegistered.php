<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\Username;

final class MemberWasRegistered implements MemberEvent
{
    private string $id;
    private string $username;
    private string $registeredAt;

    public function __construct(
        MemberId $id,
        Username $username,
        DateTimeInterface $registeredAt
    ) {
        $this->id = $id->toString();
        $this->username = $username->toString();
        $this->registeredAt = $registeredAt->format('Y-m-d H:i:d');
    }

    final public function memberId(): MemberId
    {
        return MemberId::fromString($this->id);
    }

    final public function username(): Username
    {
        return Username::fromString($this->username);
    }

    final public function registeredAt(): DateTimeInterface
    {
        return new DateTimeImmutable($this->registeredAt);
    }
}
