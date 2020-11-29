<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use function json_encode;

final class OptionListValueTest extends TestCase
{
    public function test_it_should_not_allow_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('List of options is empty, but "ListOptionValue[]" was expected.');
        OptionListValue::fromArray([]);
    }

    public function test_it_should_not_be_empty(): void
    {
        $value = OptionListValue::withElements(1);
        $this->assertFalse($value->isEmpty());
    }

    public function test_it_should_be_converted_to_string(): void
    {
        $this->assertSame(
            '[{"id":1,"value":"Option 1","label":"Label 1"}]',
            OptionListValue::withElements(1)->toString()
        );
        $this->assertSame(
            '[{"id":1,"value":"Option 1","label":"Label 1"},'
            . '{"id":2,"value":"Option 2","label":"Label 2"},'
            . '{"id":3,"value":"Option 3","label":"Label 3"}]',
            OptionListValue::withElements(3)->toString()
        );
    }

    public function test_it_should_be_converted_to_labels(): void
    {
        $this->assertSame('options(Label 1)', OptionListValue::withElements(1)->toTypedString());
        $this->assertSame(
            'options(Label 1;Label 2;Label 3)',
            OptionListValue::withElements(3)->toTypedString()
        );
    }

    public function test_it_should_not_allow_zero_elements(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of options "0" is not greater than "0".');
        OptionListValue::withElements(0);
    }

    public function test_it_should_create_from_json_string(): void
    {
        $value = OptionListValue::fromString(
            json_encode(
                [
                    [
                        'id' => 1,
                        'value' => 'opt 1',
                        'label' => 'label 1',
                    ],
                    [
                        'id' => 5,
                        'value' => 'opt 5',
                        'label' => 'Some name',
                    ],
                ]
            )
        );

        self::assertSame(
            '[{"id":1,"value":"opt 1","label":"label 1"},{"id":5,"value":"opt 5","label":"Some name"}]',
            $value->toString()
        );
        self::assertSame('options(label 1;Some name)', $value->toTypedString());
    }
}
