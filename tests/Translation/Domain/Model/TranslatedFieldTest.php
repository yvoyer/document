<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Translation\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Translation\Domain\Model\TranslatedField;
use Star\Component\Document\Translation\Domain\Model\Strategy;

final class TranslatedFieldTest extends TestCase
{
    public function test_it_should_return_the_translated_value(): void
    {
        $field = TranslatedField::withSingleTranslation(
            'field',
            'value',
            'en',
            new Strategy\ThrowExceptionWhenNotDefined()
        );
        self::assertSame('value', $field->toTranslatedString('en'));
    }

    public function test_it_should_return_the_default_value_when_no_translated_value_exists(): void
    {
        $field = TranslatedField::withSingleTranslation(
            'field',
            'value',
            'en',
            new Strategy\ReturnDefaultValue('default')
        );
        self::assertSame('default', $field->toTranslatedString('fr'));
    }

    public function test_it_should_throw_exception_when_locale_not_set(): void
    {
        $field = TranslatedField::withSingleTranslation(
            'field',
            'content',
            'en',
            new Strategy\ThrowExceptionWhenNotDefined()
        );

        $this->expectException(Strategy\MissingLocaleMap::class);
        $this->expectErrorMessage('No translation map for field "field" could be found for locale "fr".');
        $field->toTranslatedString('fr');
    }
}
