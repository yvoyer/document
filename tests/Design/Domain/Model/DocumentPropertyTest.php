<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class DocumentPropertyTest extends TestCase
{
    /**
     * @var DocumentProperty
     */
    private $property;

    public function setUp()
    {
        $this->property = DocumentProperty::fromDefinition(
            $this->createMock(DocumentDesigner::class),
            new PropertyDefinition(new PropertyName('name'), new NullType())
        );
    }

    public function test_it_should_match_definition_name()
    {
        $this->assertTrue($this->property->matchName(new PropertyName('name')));
        $this->assertFalse($this->property->matchName(new PropertyName('not name')));
    }
}
