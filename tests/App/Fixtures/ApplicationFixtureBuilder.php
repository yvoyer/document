<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use App\Tests\Assertions\Design\DocumentDesignAssertion;
use DateTimeImmutable;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentType;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocumentTypes;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
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

    public function assertDocument(DocumentTypeId $id, string $locale): DocumentDesignAssertion
    {
        $this->queryBus->dispatchQuery($query = new FindSchemaForDocumentTypes($locale, $id));

        return new DocumentDesignAssertion($query->getSingleSchema($id));
    }

    public function doCommand(Command $command): void
    {
        $this->commandBus->dispatchCommand($command);
    }

    public function dispatchQuery(Query $query): void
    {
        $this->queryBus->dispatchQuery($query);
    }

    public function newDocumentType(
        string $name,
        string $locale,
        DocumentOwner $owner
    ): DocumentTypeFixture {
        $this->doCommand(
            new CreateDocumentType(
                $id = DocumentTypeId::random(),
                DocumentName::fromLocalizedString($name, $locale),
                $owner,
                new DateTimeImmutable()
            )
        );

        return new DocumentTypeFixture($id, $this);
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
