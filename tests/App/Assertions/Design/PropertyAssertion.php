<?php declare(strict_types=1);

namespace App\Tests\Assertions\Design;

use PHPUnit\Framework\Assert;
use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;

final class PropertyAssertion
{
    private PropertyMetadata $metadata;

    public function __construct(PropertyMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    public function assertTypeIsText(): self
    {
        Assert::assertSame('', $this->metadata);

        return $this;
    }

    public function assertContainsNoOptions(): self
    {
        Assert::assertSame('', $this->metadata);

        return $this;
    }
}
