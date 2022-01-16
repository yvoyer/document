<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Assert\Assertion;
use Closure;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocumentSchema;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\Query;
use Traversable;

final class FindSchemaForDocuments implements Query
{
    /**
     * @var DocumentId[]
     */
    private array $documentIds;

    private Closure $schemas;

    public function __construct(DocumentId $first, DocumentId ...$others)
    {
        $this->documentIds = \array_merge([$first], $others);
    }

    /**
     * @return array|DocumentId[]
     */
    public function documentIdentities(): array
    {
        return $this->documentIds;
    }

    /**
     * @return array|int[]
     */
    public function documentIntIds(): array
    {
        return \array_map(
            function (DocumentId $id): string {
                return $id->toString();
            },
            $this->documentIds
        );
    }

    public function __invoke($result): void
    {
        Assertion::isInstanceOf($result, Closure::class);
        $this->schemas = $result;
    }

    /**
     * @return ReadOnlyDocumentSchema[]|Traversable
     */
    public function getResult(): Traversable
    {
        return \call_user_func($this->schemas);
    }

    /**
     * @return ReadOnlyDocumentSchema[]
     */
    public function getFoundSchemas(): array
    {
        return \iterator_to_array($this->getResult());
    }

    public function getSchemaWithId(DocumentId $documentId): ReadOnlyDocumentSchema
    {
        return $this->getFoundSchemas()[$documentId->toString()];
    }
}
