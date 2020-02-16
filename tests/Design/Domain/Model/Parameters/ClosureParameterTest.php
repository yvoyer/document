<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;

final class ClosureParameterTest extends TestCase
{
    public function test_it_should_configure_parameter_data(): void
    {
        $parameter = new ClosureParameter(
            'name',
            function () {
            }
        );
        $data = $parameter->toParameterData();
        $new = $data->createParameter();
        $this->assertInstanceOf(ClosureParameter::class, $new);
        $this->assertSame('name', $new->getName());
    }
}
