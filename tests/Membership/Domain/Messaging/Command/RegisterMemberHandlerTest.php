<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Membership\Domain\Messaging\Command;

use DateTimeImmutable;
use Star\Component\Document\Membership\Domain\Messaging\Command\RegisterMember;
use Star\Component\Document\Membership\Domain\Messaging\Command\RegisterMemberHandler;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Membership\Domain\Model\MemberAggregate;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\Username;
use Star\Component\Document\Membership\Infrastructure\Persistence\InMemory\MemberCollection;
use Star\Component\DomainEvent\EventPublisher;

final class RegisterMemberHandlerTest extends TestCase
{
    public function test_should_persist_new_member(): void
    {
        $members = new MemberCollection($this->createMock(EventPublisher::class));
        $handler = new RegisterMemberHandler($members);

        self::assertCount(0, $members);

        $handler(
            new RegisterMember(
                $memberId = MemberId::asUUid(),
                Username::fromString('user'),
                new DateTimeImmutable()
            )
        );

        self::assertCount(1, $members);
        self::assertInstanceOf(MemberAggregate::class, $members->getMemberWithId($memberId));
    }
}
