<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Infrastructure\Persistence\Doctrine;

use Star\Component\Document\Membership\Domain\Model\MemberAggregate;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\MemberRepository;
use Star\Component\DomainEvent\AggregateRoot;
use Star\Component\DomainEvent\Ports\Doctrine\DBALEventStore;

final class MemberDBALEventStore extends DBALEventStore implements MemberRepository
{
    protected function tableName(): string
    {
        return 'event_member';
    }

    protected function createAggregateFromStream(array $events): AggregateRoot
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    protected function handleNoEventFound(string $id): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function getMemberWithId(MemberId $id): MemberAggregate
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function saveMember(MemberAggregate $member): void
    {
        $this->persistAggregate($member->getIdentity()->toString(), $member);
    }
}
