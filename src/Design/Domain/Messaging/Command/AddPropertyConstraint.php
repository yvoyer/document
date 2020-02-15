<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class AddPropertyConstraint implements Command
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
     * @var PropertyConstraint
     */
    private $constraint;

    public function __construct(
        DocumentId $documentId,
        PropertyName $name,
        PropertyConstraint $constraint
    ) {
        $this->documentId = $documentId;
        $this->name = $name;
        $this->constraint = $constraint;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function name(): PropertyName
    {
        return $this->name;
    }

    public function constraint(): PropertyConstraint
    {
        return $this->constraint;
    }
}
