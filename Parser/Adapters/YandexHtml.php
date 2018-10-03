<?php
/**
 * Created by PhpStorm.
 * User: pankov
 * Date: 10/2/18
 * Time: 8:22 PM
 */

namespace App\Parser\Adapters;


class YandexHtml extends HtmlDynamicAbstract
{

    /**
     * Парсинг страницы
     *
     * @return array
     * @throws \App\Exceptions\FailParsedException
     */
    public function parse(): array
    {
        $tracksNodes = $this->getPageDom()->find('.lightlist_tracks .d-track');
        $tracks = [];
        foreach ($tracksNodes as $trackNode) {

            $artist = $trackNode->find('.d-track__artists')->text(true);

            $track = $trackNode->find('.d-track__title')->text();

            $image = $trackNode->find('.d-track-cover')->getAttribute('src');

            $tracks[] = new \App\Entities\Track($artist, $track, $image);
        }

        return $tracks;
    }
}