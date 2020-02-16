<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;

final class BooleanLabelTest extends TestCase
{
    public function test_it_should_configure_parameter_data(): void
    {
        $parameter = new BooleanLabel('vrai', 'faux');
        $data = $parameter->toParameterData();
        $new = $data->createParameter();
        $this->assertInstanceOf(BooleanLabel::class, $new);
        $this->assertSame('label', $new->getName());
    }
}
