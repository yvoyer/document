<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentType;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateDocument implements Command
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentType
     */
    private $type;

    public function __construct(DocumentId $id, DocumentType $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function documentId(): DocumentId
    {
        return $this->id;
    }

    public function type(): DocumentType
    {
        return $this->type;
    }
}
