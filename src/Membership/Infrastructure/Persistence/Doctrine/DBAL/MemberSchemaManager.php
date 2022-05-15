<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Membership\Domain\Model\Events\MemberWasRegistered;
use Star\Component\DomainEvent\EventListener;

final class MemberSchemaManager implements EventListener
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function onMemberRegistered(MemberWasRegistered $event): void
    {
        $this->connection->insert(
            'member',
            [
                'id' => $event->memberId()->toString(),
                'name' => $event->username()->toString(),
                'created_at' => $event->registeredAt()->format('Y-m-d H:i:s'),
                'updated_at' => $event->registeredAt()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function listensTo(): array
    {
        return [
            MemberWasRegistered::class => 'onMemberRegistered',
        ];
    }
}
