<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;

final class AllowMultipleOptionsTest extends TestCase
{
    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new AllowMultipleOptions();
        $this->assertEquals($source, AllowMultipleOptions::fromParameterData($source->toParameterData()));
    }
}
