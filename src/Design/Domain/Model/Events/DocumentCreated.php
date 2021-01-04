<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentType;
use Star\Component\Document\Design\Domain\Model\Schema\StringDocumentType;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

final class DocumentCreated implements DocumentEvent
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    public function __construct(DocumentId $id, DocumentType $type)
    {
        $this->id = $id->toString();
        $this->type = $type->toString();
    }

    public function documentId(): DocumentId
    {
        return DocumentId::fromString($this->id);
    }

    public function documentType(): DocumentType
    {
        return new StringDocumentType($this->type);
    }

    public static function fromPayload(array $payload): CreatedFromPayload
    {
        return new self(
            DocumentId::fromString($payload['id']),
            new StringDocumentType($payload['type'])
        );
    }
}
