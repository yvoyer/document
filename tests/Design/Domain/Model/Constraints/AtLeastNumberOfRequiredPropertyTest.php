<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Tools\DocumentBuilder;

final class AtLeastNumberOfRequiredPropertyTest extends TestCase
{
    public function test_it_should_not_allow_negative_number(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of required field "-1" is not greater than "0".');
        new AtLeastNumberOfRequiredProperty(-1);
    }

    public function test_it_should_not_allow_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of required field "0" is not greater than "0".');
        new AtLeastNumberOfRequiredProperty(0);
    }

    public function test_it_should_have_at_least_one_required_property_on_publish(): void
    {
        $document = DocumentBuilder::createDocument()
            ->withConstraint(new AtLeastNumberOfRequiredProperty(1))
            ->createBoolean('name')->endProperty()
            ->getDocument();
        $this->assertFalse($document->isPublished());

        $document->publish();

        $this->assertTrue($document->isPublished());
    }
}
