<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ValueDefinition;

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
     * @var ValueDefinition
     */
    private $value;

    /**
     * @param DocumentId $documentId
     * @param PropertyName $name
     * @param ValueDefinition $value
     */
    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        ValueDefinition $value
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return DocumentId
     */
    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    /**
     * @return PropertyName
     */
    public function name(): PropertyName
    {
        return $this->name;
    }

    /**
     * @return ValueDefinition
     */
    public function value(): ValueDefinition
    {
        return $this->value;
    }
}
