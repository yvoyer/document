<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Messaging\Command;

use Star\Component\Document\Membership\Domain\Model\MemberAggregate;
use Star\Component\Document\Membership\Domain\Model\MemberRepository;

final class RegisterMemberHandler
{
    private MemberRepository $members;

    public function __construct(MemberRepository $members)
    {
        $this->members = $members;
    }

    public function __invoke(RegisterMember $command): void
    {
        $member = MemberAggregate::registered(
            $command->memberId(),
            $command->username(),
            $command->registeredAt()
        );

        $this->members->saveMember($member);
    }
}
