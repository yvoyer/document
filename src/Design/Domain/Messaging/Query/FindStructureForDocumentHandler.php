<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Doctrine\DBAL\Connection;

final class FindStructureForDocumentHandler
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(FindStructureForDocument $query): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
