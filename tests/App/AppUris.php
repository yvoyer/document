<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use Star\Component\Document\Design\Domain\Model\DocumentId;

final class AppUris extends RequestFactory
{
    public static function dashboard(): RequestFactory
    {
        return self::get('/');
    }

    public static function documentList(): RequestFactory
    {
        return self::get('/documents');
    }

    public static function documentShow(DocumentId $id): RequestFactory
    {
        return self::get(\sprintf('/documents/%s', $id->toString()));
    }

    public static function documentCreate(): RequestFactory
    {
        return self::post('/documents');
    }
}
