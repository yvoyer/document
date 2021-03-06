<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Behavior\State;

use Star\Component\Document\DataEntry\Domain\Model\Events\ActionWasPerformed;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Model\Behavior\State\StateBehavior;
use Star\Component\Document\Design\Domain\Model\Behavior\State\TransitStateRecord;
use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateMachine;
use function array_pop;

final class StateBehaviorTest extends TestCase
{
    /**
     * @var StateMachine
     */
    private $machine;

    protected function setUp(): void
    {
        $this->machine = StateBuilder::build()
            ->allowTransition('publish', 'draft', 'published')
            ->allowTransition('archive', 'published', 'archived')
            ->create('draft');
    }

    public function test_it_should_handle_state_transitions_on_document(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->attachBehavior('state', StateBehavior::fromMachine($this->machine))
            ->getSchema();
        $record = RecordAggregate::withValues(RecordId::fromString('id'), $schema);
        $record->uncommitedEvents(); // reset

        $this->assertSame('state(draft)', $record->getValue('state')->toTypedString());

        $record->executeAction(new TransitStateRecord('state', 'publish'));

        $this->assertSame('state(published)', $record->getValue('state')->toTypedString());

        $events = $record->uncommitedEvents();
        self::assertCount(2, $events);
        /**
         * @var ActionWasPerformed $event
         */
        $event = array_pop($events);
        self::assertInstanceOf(ActionWasPerformed::class, $event);
        self::assertSame('id', $event->recordId()->toString());
        self::assertSame(
            'Performing transition "publish" on property "state".',
            $event->action()->toHumanReadable()
        );
    }
}
