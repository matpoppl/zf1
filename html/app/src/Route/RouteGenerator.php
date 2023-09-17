<?php

namespace App\Route;

use App\Route\Entity\RouteRepository;
use App\I18n\Transliterator\AsciiTransliterator;
use App\I18n\Locale\LocaleInterface;

class RouteGenerator
{
    /** @var RouteRepository */
    private $routeRepo;

    public function __construct(RouteRepository $routeRepo)
    {
        $this->routeRepo = $routeRepo;
    }

    public function generateUniqueFrom($site, $locale, array $pathParts)
    {
        $urlFormatter = new PathFormatter(new AsciiTransliterator($locale->getLocale(), $locale->getCharset()));

        $name = $urlFormatter->format(array_shift($pathParts));
        $lang = '';
        $hash = '';
        $prefix = '/';
        $suffix = '.html';

        for ($i = 0; $i < 20; $i++) {
            switch ($i) {
                case 0:
                    break;
                case 1:
                    // lang/name.html
                    $lang = $locale;
                    break;
                case 2:
                    // reset
                    $lang = '';
                    // dir/dir/name.html
                    $prefix .= $urlFormatter->format($pathParts) . '/';
                    break;
                default:
                    // dir/dir/name-hash.html
                    $hash = '-' . md5(microtime(true), 0, 4);
                    break;
            }

            $url = $lang . $prefix . $name . $hash . $suffix;

            $match = $this->routeRepo->fetchBySiteUrl($site, $url);

            if (! $match) {
                return $url;
            }
        }

        throw new \RuntimeException('Unique url generation overflow');
    }
}
