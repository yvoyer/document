<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use stdClass;
use function sprintf;

final class TypeDataTest extends TestCase
{
    public function test_it_should_throw_exception_when_class_do_not_implement_interface(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Index "class" with value "stdClass" was expected to implement "%s".',
                PropertyType::class
            )
        );
        new TypeData(stdClass::class);
    }

    public function test_it_should_create_type_from_string(): void
    {
        $data = new TypeData(NullType::class);
        $this->assertInstanceOf(NullType::class, $data->createType());
    }

    public function test_it_should_create_from_string(): void
    {
        $data = TypeData::fromArray(['class' => NullType::class, 'arguments' => []]);
        $this->assertInstanceOf(NullType::class, $data->createType());
    }
}
