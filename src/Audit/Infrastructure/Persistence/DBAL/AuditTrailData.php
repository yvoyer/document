<?php declare(strict_types=1);

namespace Star\Component\Document\Audit\Infrastructure\Persistence\DBAL;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Audit\Domain\Model\UpdatedBy;

trait AuditTrailData
{
    protected function withAuditForInsert(
        array $data,
        AuditDateTime $createdAt,
        UpdatedBy $createdBy
    ): array
    {
        return \array_merge(
            $data,
            [
                'created_by' => $createdBy->toString(),
                'created_at' => $createdAt->toDateTimeFormat(),
                'updated_by' => $createdBy->toString(),
                'updated_at' => $createdAt->toDateTimeFormat(),
            ]
        );
    }

    protected function withAuditForUpdate(
        array $data,
        AuditDateTime $updatedAt,
        UpdatedBy $updatedBy
    ): array {
        return \array_merge(
            $data,
            [
                'updated_by' => $updatedBy->toString(),
                'updated_at' => $updatedAt->toDateTimeFormat(),
            ]
        );
    }
}
