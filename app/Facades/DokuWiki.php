<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getPage(string $id)
 * @method static bool savePage(string $page, string $text, string $summary, bool $isminor)
 * @method static array listMedia(string $namespace, string $pattern, int $depth, bool $hash)
 * @method static array saveMedia(string $media, string $base64, bool $overwrite)
 * @method static string convertHtmlToDokuWiki(string $html)
 * @method static mixed call(string $method, array $params = [])
 */
class DokuWiki extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'dokuwiki';
    }
}

