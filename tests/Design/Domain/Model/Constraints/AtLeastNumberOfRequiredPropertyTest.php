<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraints\AtLeastNumberOfRequiredProperty;

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
}
