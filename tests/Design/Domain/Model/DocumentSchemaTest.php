<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

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

    public function test_it_should_add_text(): string
    {
        $this->schema->addText('name');
        $this->assertSame(
            $expected = '{"id":"d-id","properties":{"name":{"type":"string"}}}',
            $this->schema->toString()
        );

        return $expected;
    }

    /**
     * @param string $string
     * @depends test_it_should_add_text
     */
    public function test_it_should_build_text_from_string(string $string): void
    {
        $schema = DocumentSchema::fromString($string);
        $this->assertInstanceOf(StringType::class, $schema->getPropertyType('name'));
    }
}
