<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;

final class NullParameterTest extends TestCase
{
    public function test_it_should_configure_parameter_data(): void
    {
        $parameter = new NullParameter();
        $data = $parameter->toParameterData();
        $new = $data->createParameter();
        $this->assertInstanceOf(NullParameter::class, $new);
    }
}
