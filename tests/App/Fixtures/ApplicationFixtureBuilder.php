<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use DateTimeImmutable;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Membership\Domain\Messaging\Command\RegisterMember;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\Username;
use Star\Component\DomainEvent\Messaging\Command;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Star\Component\DomainEvent\Messaging\Query;
use Star\Component\DomainEvent\Messaging\QueryBus;

final class ApplicationFixtureBuilder
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function doCommand(Command $command): void
    {
        $this->commandBus->dispatchCommand($command);
    }

    public function dispatchQuery(Query $query): void
    {
        $this->queryBus->dispatchQuery($query);
    }

    public function newDocument(DocumentOwner $owner): DocumentFixture
    {
        $this->doCommand(
            CreateDocument::emptyDocument(
                $id = DocumentId::random(),
                $owner,
                new DateTimeImmutable()
            )
        );

        return new DocumentFixture($id, $this);
    }

    public function newMember(): MembershipFixture
    {
        $this->doCommand(
            new RegisterMember(
                $id = MemberId::asUUid(),
                Username::fromString(\uniqid('username-')),
                new DateTimeImmutable()
            )
        );

        return new MembershipFixture($id, $this);
    }
}
