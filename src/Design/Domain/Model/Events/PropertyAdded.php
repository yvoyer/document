<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class PropertyAdded implements DocumentEvent
{
    /**
     * @var DocumentId
     */
    private $document;

    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var PropertyType
     */
    private $type;

    public function __construct(
        DocumentId $document,
        PropertyName $name,
        PropertyType $type
    ) {
        $this->document = $document;
        $this->name = $name;
        $this->type = $type;
    }

    public function documentId(): DocumentId
    {
        return $this->document;
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
