<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateProperty implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var PropertyType
     */
    private $type;

    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        PropertyType $type
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->type = $type;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function name(): PropertyName
    {
        return $this->name;
    }

    public function type(): PropertyType
    {
        return $this->type;
    }
}
