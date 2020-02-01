<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

final class AddValueTransformerOnProperty implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $propertyName;

    /**
     * @var TransformerIdentifier
     */
    private $transformer;

    public function __construct(
        DocumentId $documentId,
        PropertyName $propertyName,
        TransformerIdentifier $transformer
    ) {
        $this->documentId = $documentId;
        $this->propertyName = $propertyName;
        $this->transformer = $transformer;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function propertyName(): PropertyName
    {
        return $this->propertyName;
    }

    public function transformerId(): TransformerIdentifier
    {
        return $this->transformer;
    }
}
