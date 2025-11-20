<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Translation\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Translation\Domain\Model\Strategy\NotAllowedEmptyTranslation;
use Star\Component\Document\Translation\Domain\Model\TranslatedField;
use Star\Component\Document\Translation\Domain\Model\Strategy;

final class TranslatedFieldTest extends TestCase
{
    public function test_it_should_return_the_translated_value(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'value',
            'en',
            new Strategy\AlwaysThrowExceptions()
        );
        self::assertSame('value', $field->toTranslatedString('en'));
    }

    public function test_it_should_return_the_default_value_when_no_translated_value_exists(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'value',
            'en',
            new Strategy\ReturnDefaultValue('default')
        );
        self::assertSame('default', $field->toTranslatedString('fr'));
    }

    public function test_it_should_throw_exception_when_locale_not_set(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'content',
            'en',
            new Strategy\AlwaysThrowExceptions()
        );

        $this->expectException(Strategy\MissingLocaleMap::class);
        $this->expectErrorMessage('No translation map for field "field" could be found for locale "fr".');
        $field->toTranslatedString('fr');
    }

    public function test_it_should_set_new_translation(): void
    {
        $old = TranslatedField::forDefaultLocale('name', 'old', 'en');
        $new = $old->updateLocalizedValue('new', 'en');

        self::assertSame('old', $old->toTranslatedString('en'));
        self::assertSame('new', $new->toTranslatedString('en'));
    }

    public function test_it_should_remove_previous_translation(): void
    {
        $old = TranslatedField::forDefaultLocale(
            'name',
            'default-name',
            'default',
            new Strategy\ReturnDefaultValue('some-default')
        );
        $frName = $old->updateLocalizedValue('old-fr', 'fr');

        self::assertSame('default-name', $frName->toTranslatedString('default'));
        self::assertSame('old-fr', $frName->toTranslatedString('fr'));

        $newName = $old->updateLocalizedValue('', 'fr');

        self::assertSame('default-name', $newName->toTranslatedString('default'));
        self::assertSame('some-default', $newName->toTranslatedString('fr'));
    }

    public function test_it_should_throw_exception_when_updating_to_an_empty_value(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'value',
            'fr',
            new Strategy\AlwaysThrowExceptions()
        );

        $this->expectException(Strategy\NotAllowedEmptyTranslation::class);
        $this->expectErrorMessage('Field "field" is not allowed to be empty for locale "en".');
        $field->updateLocalizedValue('', 'en');
    }

    public function test_it_should_trim_input_on_create(): void
    {
        $this->expectException(Strategy\NotAllowedEmptyTranslation::class);
        $this->expectErrorMessage('Field "field" is not allowed to be empty for default locale "en".');
        TranslatedField::forDefaultLocale(
            'field',
            '    ',
            'en',
            new Strategy\AlwaysThrowExceptions()
        );
    }

    public function test_it_should_trim_input_on_update(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'value',
            'en',
            new Strategy\AlwaysThrowExceptions()
        );

        $this->expectException(Strategy\NotAllowedEmptyTranslation::class);
        $this->expectErrorMessage('Field "field" is not allowed to be empty for locale "fr".');
        $field->updateLocalizedValue('   ', 'fr');
    }

    public function test_it_should_not_allow_empty_content_of_default_locale(): void
    {
        $this->expectException(NotAllowedEmptyTranslation::class);
        $this->expectErrorMessage('Field "field" is not allowed to be empty for default locale "en".');
        TranslatedField::forDefaultLocale(
            'field',
            '',
            'en',
            new Strategy\AlwaysThrowExceptions()
        );
    }

    public function test_it_should_not_allow_removing_content_of_default_locale(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'value',
            'en',
            new Strategy\AlwaysThrowExceptions()
        );
        $this->expectException(NotAllowedEmptyTranslation::class);
        $this->expectErrorMessage('Field "field" is not allowed to be empty for default locale "en".');
        $field->updateLocalizedValue('', 'en');
    }

    public function test_it_should_configure_to_default_value_when_removing_default_locale_content(): void
    {
        $field = TranslatedField::forDefaultLocale(
            'field',
            'value',
            'en',
            new Strategy\ReturnDefaultValue('default')
        );
        $new = $field->updateLocalizedValue('', 'en');

        self::assertSame('default', $new->toTranslatedString('en'));
    }
}
