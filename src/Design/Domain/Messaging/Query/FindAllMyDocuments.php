<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Closure;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\DomainEvent\Messaging\Query;
use Traversable;

final class FindAllMyDocuments implements Query
{
    private DocumentOwner $owner;
    private Closure $result;
    private string $locale;

    public function __construct(DocumentOwner $owner, string $locale)
    {
        $this->owner = $owner;
        $this->locale = $locale;
    }

    final public function owner(): DocumentOwner
    {
        return $this->owner;
    }

    final public function locale(): string
    {
        return $this->locale;
    }

    public function __invoke($result): void
    {
        $this->result = $result;
    }

    /**
     * @return ReadOnlyDocument[]|Traversable
     */
    public function getResult(): Traversable
    {
        return \call_user_func($this->result);
    }

    /**
     * @return ReadOnlyDocument[]
     */
    public function getResultArray(): array
    {
        return \iterator_to_array($this->getResult());
    }
}
