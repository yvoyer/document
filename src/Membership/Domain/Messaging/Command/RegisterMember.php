<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Messaging\Command;

use DateTimeInterface;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\Username;
use Star\Component\DomainEvent\Messaging\Command;

final class RegisterMember implements Command
{
    private MemberId $id;
    private Username $username;
    private DateTimeInterface $registeredAt;

    public function __construct(
        MemberId $id,
        Username $username,
        DateTimeInterface $registeredAt
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->registeredAt = $registeredAt;
    }

    final public function memberId(): MemberId
    {
        return $this->id;
    }

    final public function username(): Username
    {
        return $this->username;
    }

    final public function registeredAt(): DateTimeInterface
    {
        return $this->registeredAt;
    }
}
