<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Membership\Domain\Model;

use DateTimeImmutable;
use Star\Component\Document\Membership\Domain\Model\Events\MemberWasRegistered;
use Star\Component\Document\Membership\Domain\Model\MemberAggregate;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\Username;

final class MemberAggregateTest extends TestCase
{
    public function test_something(): void
    {
        $member = MemberAggregate::registered(
            MemberId::fromString('mid'),
            Username::fromString('name'),
            new DateTimeImmutable('2000-01-02 11:22:33')
        );

        $events = $member->uncommitedEvents();
        self::assertContainsOnlyInstancesOf(MemberWasRegistered::class, $events);
        /**
         * @var MemberWasRegistered $event
         */
        $event = $events[0];
        self::assertSame('mid', $event->memberId()->toString());
        self::assertSame('name', $event->username()->toString());
        self::assertSame('2000-01-02', $event->registeredAt()->format('Y-m-d'));
    }
}
