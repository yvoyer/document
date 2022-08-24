<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use App\Tests\Assertions\Design\DocumentTypeAssertion;
use Doctrine\DBAL\Connection;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
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
    private Connection $connection;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        Connection $connection
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->connection = $connection;

    }

    public function assertDocumentType(DocumentTypeId $id): DocumentTypeAssertion
    {
        return new DocumentTypeAssertion($id, $this->connection);
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
                AuditDateTime::fromNow()
            )
        );

        return $this->loadDocumentType($id);
    }

    public function loadDocumentType(DocumentTypeId $typeId): DocumentTypeFixture
    {
        return new DocumentTypeFixture($typeId, $this);
    }

    public function newMember(): MembershipFixture
    {
        $this->doCommand(
            new RegisterMember(
                $id = MemberId::asUUid(),
                Username::fromString(\uniqid('username-')),
                AuditDateTime::fromNow()
            )
        );

        return $this->loadMember($id);
    }

    public function loadMember(MemberId $id): MembershipFixture
    {
        return new MembershipFixture($id, $this);
    }
}
