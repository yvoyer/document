<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

final class AppUris extends RequestFactory
{
    public static function dashboard(): RequestFactory
    {
        return self::get('/');
    }

    public static function setup(): RequestFactory
    {
        return self::get('/setup');
    }
}
