<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentCreated implements DocumentEvent
{
    /**
     * @var string
     */
    private $id;

    public function __construct(DocumentId $id)
    {
        $this->id = $id->toString();
    }

    public function documentId(): DocumentId
    {
        return DocumentId::fromString($this->id);
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(DocumentId::fromString($payload['id']));
    }
}
