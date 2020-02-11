<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\Types;

final class DocumentSchemaTest extends TestCase
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    public function setUp(): void
    {
        $this->schema = new DocumentSchema(DocumentId::fromString('d-id'));
    }

    private function assertArrayIsJson(array $data, string $actual): void
    {
        $this->assertJson($actual, \json_encode($data));
    }

    public function test_it_should_set_the_document_id(): void
    {
        $this->assertArrayIsJson(
            [
                'id' => 'd-id',
                'properties' => [],
            ],
            $string = $this->schema->toString()
        );
        $schema = DocumentSchema::fromString($string);
        $this->assertSame('d-id', $schema->getIdentity()->toString());
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_add_property(): void
    {
        $type = new Types\NullType();
        $this->schema->addProperty('name', $type);
        $this->assertArrayIsJson(
            [
                'id' => 'd-id',
                'properties' => [
                    'name' => [
                        'type-class' => \get_class($type),
                        'type' => '',
                    ],
                ],
            ],
            $string = $this->schema->toString()
        );

        $schema = DocumentSchema::fromString($string);
        $this->assertInstanceOf(
            \get_class($type),
            $schema->getDefinition('name')->getType()
        );
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_serialize_constraints(): void
    {
        $property = 'prop';
        $constraint = 'const';

        $this->schema->addProperty($property, new Types\NullType());
        $this->schema->addConstraint($property, $constraint, new NoConstraint());
        $this->assertArrayIsJson(
            [
                'id' => 'd-id',
                'properties' => [
                    $property => [
                        'constraints' => [
                            $constraint => [
                                'class' => Types\NullType::class,
                                'arguments' => [],
                            ],
                        ],
                    ],
                ],
            ],
            $string = $this->schema->toString()
        );

        $schema = DocumentSchema::fromString($string);
        $definition = $schema->getDefinition($property);
        $this->assertTrue($definition->hasConstraint($constraint));
        $this->assertCount(1, $definition->getConstraints());
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_serialize_transformers(): void
    {
        $property = 'prop';
        $constraint = 'const';

        $this->schema->addProperty($property, new Types\NullType());
        $this->schema->addConstraint($property, $constraint, new NoConstraint());
        $this->assertArrayIsJson(
            [
                'id' => 'd-id',
                'properties' => [
                    $property => [
                        'constraints' => [],
                        'transformers' => [
                            ''
                        ],
                    ],
                ],
            ],
            $string = $this->schema->toString()
        );

        $schema = DocumentSchema::fromString($string);
        $definition = $schema->getDefinition($property);
        $this->assertTrue($definition->hasConstraint($constraint));
        $this->assertCount(1, $definition->getConstraints());
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_throw_exception_when_property_type_not_supported(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Class "Some\Path" does not exist.');
        DocumentSchema::fromString(
            \json_encode(
                [
                    'id' => 'dId',
                    'properties' => [
                        'name' => [
                            'type' => [
                                'class' => 'Some\Path',
                                'arguments' => [],
                            ],
                        ],
                    ],
                ]
            )
        );
    }

    public function test_it_should_clone_schema(): void
    {
        $this->schema->addProperty('empty', new Types\NullType());
        $this->schema->addProperty('with-constraint', new Types\NullType());
        $this->schema->addConstraint('with-constraint', 'const', new NoConstraint());

        $duplicate = $this->schema->clone(DocumentId::fromString('new-id'));
        $expected = \json_decode($this->schema->toString(), true);
        $expected['id'] = 'new-id';

        $this->assertJsonStringEqualsJsonString(\json_encode($expected), $duplicate->toString());
    }
}
