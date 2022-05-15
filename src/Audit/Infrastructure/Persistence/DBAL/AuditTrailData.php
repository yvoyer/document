<?php declare(strict_types=1);

namespace Star\Component\Document\Audit\Infrastructure\Persistence\DBAL;

use DateTimeInterface;
use Star\Component\Document\Audit\Domain\Model\UpdatedBy;

trait AuditTrailData
{
    protected function withAuditForInsert(
        array $data,
        DateTimeInterface $createdAt,
        UpdatedBy $createdBy
    ): array
    {
        return \array_merge(
            $data,
            [
                'created_by' => $createdBy->toString(),
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
                'updated_by' => $createdBy->toString(),
                'updated_at' => $createdAt->format('Y-m-d H:i:s'),
            ]
        );
    }

    protected function withAuditForUpdate(
        array $data,
        DateTimeInterface $updatedAt,
        UpdatedBy $updatedBy
    ): array {
        return \array_merge(
            $data,
            [
                'updated_by' => $updatedBy->toString(),
                'updated_at' => $updatedAt->format('Y-m-d H:i:s'),
            ]
        );
    }
}
