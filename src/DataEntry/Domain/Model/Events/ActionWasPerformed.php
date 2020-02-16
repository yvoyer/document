<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordAction;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;

final class ActionWasPerformed implements RecordEvent
{
    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var RecordAction
     */
    private $action;

    public function __construct(RecordId $recordId, RecordAction $action)
    {
        $this->recordId = $recordId;
        $this->action = $action;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function action(): RecordAction
    {
        return $this->action;
    }
}
