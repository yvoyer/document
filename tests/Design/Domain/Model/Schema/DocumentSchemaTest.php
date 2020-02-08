<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;
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
        $this->schema->addProperty('name', new Types\NullType());
        $this->schema->addConstraint('name', 'const', new RequiresValue());
        $this->assertArrayIsJson(
            [
                'id' => 'd-id',
                'properties' => [],
                'constraints' => [
                    'const' => 'required',
                ],
            ],
            $string = $this->schema->toString()
        );

        $schema = DocumentSchema::fromString($string);
        $this->assertFalse(
            $schema->getDefinition('name')->hasConstraint('const')
        );
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
                            'type' => \json_encode(
                                [
                                    'class' => 'Some\Path',
                                    'arguments' => [],
                                ]
                            ),
                        ],
                    ],
                ]
            )
        );
    }
}
