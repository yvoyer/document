<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Behavior\State;

use ReflectionClass;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Behavior\DocumentBehavior;
use Star\Component\Document\Design\Domain\Model\Types\BehaviorType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateMachine;
use Star\Component\State\Visitor\AttributeDumper;
use Star\Component\State\Visitor\TransitionDumper;

class StateBehavior extends DocumentBehavior
{
    /**
     * @var StateMachine
     */
    private $machine;

    /**
     * @var string
     */
    private $current;

    /**
     * @param mixed[] $arguments
     */
    protected function onInit(array $arguments): void
    {
        $attributes = $arguments['attributes'];
        $transitions = $arguments['transitions'];
        $builder = StateBuilder::build();
        foreach ($transitions as $name => $transition) {
            $builder->allowTransition($name, $transition['from'], $transition['to'][0]);
        }

        foreach ($attributes as $attribute => $states) {
            $builder->addAttribute($attribute, $states);
        }

        $this->machine = $builder->create($this->current = $arguments['current']);
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        if ($value->isEmpty()) {
            $value = $this->currentState();
        }

        return $value;
    }

    public function supportsType(RecordValue $value): bool
    {
        if ($value->isEmpty()) {
            return true; // default value is override on init
        }

        return $value instanceof StateValue;
    }

    public function supportsValue(RecordValue $value): bool
    {
        return true; // always supports, since transition is validated later
    }

    public function toHumanReadableString(): string
    {
        return $this->currentState()->toTypedString();
    }

    public function onPerform(string $property, RecordValue $value): RecordValue
    {
        return StateValue::fromString($this->machine->transit($value->toString(), $property));
    }

    public function toTypeData(): TypeData
    {
        return self::createTypeData($this->machine);
    }

    private function currentState(): RecordValue
    {
        return StateValue::fromString($this->current);
    }

    private static function createTypeData(StateMachine $machine): TypeData
    {
        $machine->acceptStateVisitor($attributes = new AttributeDumper());
        $machine->acceptTransitionVisitor($transitions = new TransitionDumper());

        $reflexion = new ReflectionClass($machine);
        $property = $reflexion->getProperty('currentState');
        $property->setAccessible(true);
        $current = $property->getValue($machine);

        return new TypeData(
            BehaviorType::class,
            [
                'behavior_class' => StateBehavior::class,
                'attributes' => $attributes->getStructure(),
                'transitions' => $transitions->getStructure(),
                'current' => $current,
            ]
        );
    }

    /**
     * @param StateMachine $machine
     * @return static
     */
    public static function fromMachine(StateMachine $machine): self
    {
        return self::fromData(self::createTypeData($machine)->toArray()['arguments']);
    }
}
