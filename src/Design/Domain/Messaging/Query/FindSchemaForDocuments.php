<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Assert\Assertion;
use Closure;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\SchemaOfDocument;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\Query;
use Traversable;

final class FindSchemaForDocuments implements Query
{
    /**
     * @var DocumentId[]
     */
    private array $documentIds;
    private string $locale;
    private Closure $schemas;

    public function __construct(
        string $locale,
        DocumentId $first,
        DocumentId ...$others
    ) {
        $this->documentIds = \array_merge([$first], $others);
        $this->locale = $locale;
    }

    /**
     * @return array|DocumentId[]
     */
    public function documentIdentities(): array
    {
        return $this->documentIds;
    }

    final public function locale(): string
    {
        return $this->locale;
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
     * @return SchemaOfDocument[]|Traversable
     */
    public function getResult(): Traversable
    {
        return \call_user_func($this->schemas);
    }

    public function getSingleSchema(DocumentId $id): SchemaOfDocument
    {
        $result = $this->getAllFoundSchemas();
        Assertion::keyExists($result, $id->toString());

        return $result[$id->toString()];
    }

    /**
     * @return SchemaOfDocument[]
     */
    public function getAllFoundSchemas(): array
    {
        return \iterator_to_array($this->getResult());
    }
}
