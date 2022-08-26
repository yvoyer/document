<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordAction;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;

final class ActionWasPerformed implements RecordEvent
{
    /**
     * @var DocumentId
     */
    private $recordId;

    /**
     * @var RecordAction
     */
    private $action;

    public function __construct(DocumentId $recordId, RecordAction $action)
    {
        $this->recordId = $recordId;
        $this->action = $action;
    }

    public function recordId(): DocumentId
    {
        return $this->recordId;
    }

    public function action(): RecordAction
    {
        return $this->action;
    }
}
