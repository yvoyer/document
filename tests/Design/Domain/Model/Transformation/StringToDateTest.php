<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Tools\DocumentBuilder;

final class StringToDateTest extends TestCase
{
    public function test_it_should_add_value_transformer_to_property()
    {
        $record = DocumentBuilder::createBuilder('id')
            ->createText('text')
            ->withTransformer(new StringToDate('Y-m-d'))
            ->endProperty()
            ->startRecord('r')
            ->setValue('text', 'last year')
            ->getRecord();

        $this->assertSame(
            date('Y-m-d', strtotime('last year')),
            $record->getValue('text')->toString()
        );
    }
}
