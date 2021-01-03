<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\Command;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Star\Component\DomainEvent\Messaging\Query;
use Star\Component\DomainEvent\Messaging\QueryBus;

final class ApplicationFixtureBuilder
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

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

    public function newDocument(): DocumentFixture
    {
        $this->doCommand(new CreateDocument($id = DocumentId::random()));

        return new DocumentFixture($id, $this);
    }
}
