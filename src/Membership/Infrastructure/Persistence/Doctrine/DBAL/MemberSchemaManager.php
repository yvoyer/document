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
        $memberExists = $this->connection
            ->executeQuery(
                'SELECT * FROM member where id = :member_id',
                [
                    'member_id' => $event->memberId()->toString(),
                ]
            )
            ->rowCount();
        if ($memberExists === 1) {
            return; // todo do not create for now
        }

        // todo make real register, this will create duplicate
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
