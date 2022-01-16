<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Infrastructure\Persistence\InMemory;

use Countable;
use Star\Component\Document\Membership\Domain\Model\MemberAggregate;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\MemberNotFound;
use Star\Component\Document\Membership\Domain\Model\MemberRepository;
use Star\Component\DomainEvent\AggregateRoot;
use Star\Component\DomainEvent\DomainEvent;
use Star\Component\DomainEvent\Ports\InMemory\EventStoreCollection;

final class MemberCollection extends EventStoreCollection implements MemberRepository, Countable
{
    public function getMemberWithId(MemberId $id): MemberAggregate
    {
        return $this->loadAggregate($id->toString());
    }

    public function saveMember(MemberAggregate $member): void
    {
        $this->saveAggregate($member->getIdentity()->toString(), $member);
    }

    protected function handleAggregateNotFound(string $id): void
    {
        throw MemberNotFound::objectWithAttribute(MemberAggregate::class, 'id', $id);
    }

    protected function createAggregate(DomainEvent ...$events): AggregateRoot
    {
        return MemberAggregate::fromStream($events);
    }
}
