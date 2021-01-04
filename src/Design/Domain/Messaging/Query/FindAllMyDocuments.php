<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Star\Component\DomainEvent\Messaging\Results\CollectionQuery;

final class FindAllMyDocuments extends CollectionQuery
{
    /**
     * @return MyReadOnlyDocument[]
     */
    public function getResult(): array
    {
        return parent::getResult();
    }
}
