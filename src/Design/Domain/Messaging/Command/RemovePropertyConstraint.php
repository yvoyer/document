<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class RemovePropertyConstraint implements Command
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
     * @var string
     */
    private $constraintName;

    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        string $constraintName
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->constraintName = $constraintName;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function name(): PropertyName
    {
        return $this->name;
    }

    public function constraintName(): string
    {
        return $this->constraintName;
    }
}
