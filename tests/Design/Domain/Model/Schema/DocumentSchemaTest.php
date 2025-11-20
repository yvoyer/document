<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Schema;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraints\All;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Types;
use function get_class;
use function json_decode;
use function json_encode;

final class DocumentSchemaTest extends TestCase
{
    private DocumentSchema $schema;

    public function setUp(): void
    {
        $this->schema = new DocumentSchema(DocumentTypeId::fromString('d-id'));
    }

    private function assertArrayIsJson(array $data, string $actual): void
    {
        $this->assertJson($actual, json_encode($data));
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
        $schema = DocumentSchema::fromJsonString($string);
        $this->assertSame('d-id', $schema->getIdentity()->toString());
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_add_property(): void
    {
        $type = new Types\NullType();
        $this->schema->addProperty(
            PropertyCode::fromString('name'),
            PropertyName::fromLocalizedString('name-en', 'en'),
            $type
        );
        $this->assertArrayIsJson(
            [
                'id' => 'd-id',
                'properties' => [
                    'name' => [
                        'type-class' => get_class($type),
                        'type' => '',
                    ],
                ],
            ],
            $string = $this->schema->toString()
        );

        $schema = DocumentSchema::fromJsonString($string);
        $this->assertSame(
            'null',
            $schema->getPropertyMetadata('name')->toTypedString()
        );
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_serialize_constraints(): void
    {
        $constraint = 'const';

        $this->schema->addProperty(
            $code = PropertyCode::fromString($property = 'prop'),
            PropertyName::fromLocalizedString('name', 'en'),
            new Types\NullType()
        );
        $this->schema->addPropertyConstraint($code, $constraint, new All(new NoConstraint()));
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

        $schema = DocumentSchema::fromJsonString($string);
        $definition = $schema->getPropertyMetadata($property);
        $this->assertTrue($definition->hasConstraint($constraint));
        $this->assertCount(1, $definition->getConstraints());
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_serialize_transformers(): void
    {
        $property = 'prop';
        $constraint = 'const';

        $this->schema->addProperty(
            $code = PropertyCode::fromString($property),
            PropertyName::fromLocalizedString($property, 'en'),
            new Types\NullType()
        );
        $this->schema->addPropertyConstraint($code, $constraint, new All(new NoConstraint()));
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

        $schema = DocumentSchema::fromJsonString($string);
        $definition = $schema->getPropertyMetadata($property);
        $this->assertTrue($definition->hasConstraint($constraint));
        $this->assertCount(1, $definition->getConstraints());
        $this->assertSame($string, $schema->toString());
    }

    public function test_it_should_throw_exception_when_property_type_not_supported(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class "Some\Path" does not exist.');
        DocumentSchema::fromJsonString(
            json_encode(
                [
                    'id' => 'dId',
                    'properties' => [
                        'name' => [
                            'name' => 'a:2:{s:7:"content";s:7:"invalid";s:6:"locale";s:2:"en";}',
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
        $this->schema->addProperty(
            PropertyCode::fromString('empty'),
            PropertyName::random(),
            new Types\NullType()
        );
        $this->schema->addProperty(
            $code = PropertyCode::fromString('with-constraint'),
            PropertyName::random(),
            new Types\NullType()
        );
        $this->schema->addPropertyConstraint($code, 'const', new NoConstraint());

        $duplicate = $this->schema->clone(DocumentTypeId::fromString('new-id'));
        $expected = json_decode($this->schema->toString(), true);
        $expected['id'] = 'new-id';

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $duplicate->toString());
    }

    public function test_add_parameter(): void
    {
        $this->schema->addProperty(
            $code = PropertyCode::fromString('prop'),
            PropertyName::fromLocalizedString('name', 'en'),
            new Types\NullType()
        );
        $this->assertSame(
            json_encode(
                [
                    'id' => 'd-id',
                    'properties' => [
                        'prop' => [
                            'name' => 'a:2:{s:7:"content";s:4:"name";s:6:"locale";s:2:"en";}',
                            'type' => [
                                'class' => Types\NullType::class,
                                'arguments' => [],
                            ],
                            'constraints' => [],
                            'parameters' => [],
                        ],
                    ],
                ]
            ),
            $this->schema->toString()
        );

        $this->schema->addParameter($code, 'param', new NullParameter());

        $this->assertSame(
            json_encode(
                [
                    'id' => 'd-id',
                    'properties' => [
                        'prop' => [
                            'name' => 'a:2:{s:7:"content";s:4:"name";s:6:"locale";s:2:"en";}',
                            'type' => [
                                'class' => Types\NullType::class,
                                'arguments' => [],
                            ],
                            'constraints' => [],
                            'parameters' => [
                                'param' => [
                                    'class' => NullParameter::class,
                                    'arguments' => [],
                                ],
                            ],
                        ],
                    ],
                ]
            ),
            $this->schema->toString()
        );
    }

    public function test_parameters_should_be_cloned(): void
    {
        $this->schema->addProperty(
            $code = PropertyCode::fromString('prop'),
            PropertyName::fromLocalizedString('name', 'en'),
            new Types\NullType()
        );
        $this->schema->addParameter($code, 'param', new NullParameter());

        $this->assertTrue($this->schema->getPropertyMetadata('prop')->hasParameter('param'));

        $clone = $this->schema->clone(DocumentTypeId::random());

        $this->assertTrue($clone->getPropertyMetadata('prop')->hasParameter('param'));
        $this->assertTrue($this->schema->getPropertyMetadata('prop')->hasParameter('param'));
    }
}
