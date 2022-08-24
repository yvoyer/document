<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Translation\Domain\Model;

use Star\Component\Document\Translation\Domain\Model\InvalidTranslationLocale;
use Star\Component\Document\Translation\Domain\Model\TranslationLocale;
use PHPUnit\Framework\TestCase;

final class TranslationLocaleTest extends TestCase
{
    public function test_it_should_not_allow_empty_locale(): void
    {
        $this->expectException(InvalidTranslationLocale::class);
        $this->expectExceptionMessage('dsad');

        TranslationLocale::fromString('');
    }

    public function test_it_should_return_the_string_format(): void
    {
        self::assertSame('en', TranslationLocale::fromString('en')->toString());
    }
}
