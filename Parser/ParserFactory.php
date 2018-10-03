<?php

namespace App\Parser;

use App\Exceptions\ParserAdapterNotFoundException;
use App\Parser\Adapters\AdapterInterface;

class ParserFactory
{
    // @todo Должно лежать в конфиге
    private $adapters = [
        \App\Parser\Adapters\ShazamHtml::class => [
            'shazam\.com\/ru\/charts\/top-50',
            'shazam\.com\/ru\/charts\/top-100',

        ],
        \App\Parser\Adapters\ShazamJson::class => 'shazam\.com\/shazam\/v2.*country-chart',
        \App\Parser\Adapters\YandexHtml::class => 'music\.yandex\.ru\/chart',
    ];

    /**
     * @param string $url
     * @return AdapterInterface
     * @throws ParserAdapterNotFoundException
     */
    public function createFromUrl(string $url): AdapterInterface
    {
        foreach ($this->adapters as $adapterName => $patterns) {

            foreach ((array) $patterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $url)) {
                    return new $adapterName($url);
                }
            }
        }

        throw new ParserAdapterNotFoundException('Адаптер не найден');
    }
}