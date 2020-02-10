<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;

final class DateFormatTest extends TestCase
{
    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new DateFormat('2000-10-20');
        $this->assertEquals($source, DateFormat::fromData($source->toData()));
    }
}
