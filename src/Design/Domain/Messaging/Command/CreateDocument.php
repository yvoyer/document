<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;

final class CreateDocument implements Command
{
    /**
     * @var DocumentId
     */
    private $id;

    public function __construct(DocumentId $id)
    {
        $this->id = $id;
    }

    public function documentId(): DocumentId
    {
        return $this->id;
    }
}
