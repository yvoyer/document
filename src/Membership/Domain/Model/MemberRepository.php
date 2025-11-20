<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model;

interface MemberRepository
{
    public function isRegistered(MemberId $id): bool;

    public function getMemberWithId(MemberId $id): MemberAggregate;

    public function saveMember(MemberAggregate $member): void;
}
