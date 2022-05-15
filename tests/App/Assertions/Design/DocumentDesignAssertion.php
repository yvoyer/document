<?php declare(strict_types=1);

namespace App\Tests\Assertions\Design;

use PHPUnit\Framework\Assert;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\SchemaOfDocument;

final class DocumentDesignAssertion
{
    private SchemaOfDocument $document;

    public function __construct(SchemaOfDocument $document)
    {
        $this->document = $document;
    }

    public function assertName(string $expected): self
    {
        Assert::assertSame($expected, $this->document->getName());

        return $this;
    }

    public function assertPropertyCount(int $expected): self
    {
        Assert::assertCount($expected, $this->document->getPublicProperties());

        return $this;
    }

    public function enterPropertyWithName(string $propertyName): PropertyAssertion
    {
        return new PropertyAssertion($this->document->getPublicProperty($propertyName));
    }
}
