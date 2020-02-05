<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use PHPUnit\Framework\TestCase;

final class DocumentDesignerToSchemaTest extends TestCase
{
    /**
     * @var DocumentDesignerToSchema
     */
    private $_documentDesignerToSchema;

    public function setUp(): void
    {
        $this->_documentDesignerToSchema = new DocumentDesignerToSchema();
    }

    public function test_it_should_transform_value_before_validation(): void
    {
        $this->fail('test something');
    }
}
