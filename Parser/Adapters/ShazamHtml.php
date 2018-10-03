<?php

namespace App\Parser\Adapters;

class ShazamHtml extends HtmlDynamicAbstract
{

    /**
     * @inheritdoc
     * @throws \App\Exceptions\FailParsedException
     */
    public function parse(): array
    {
        $tracksNodes = $this->getPageDom()->find('.tracks article');

        $tracks = [];
        foreach ($tracksNodes as $trackNode) {
            $detailsNode = $trackNode->find('.details');
            $artist = $detailsNode->find('.artist')->find('.ellip')->text();
            $track = $detailsNode->find('.title')->find('.ellip')->text();

            $imageNode = $trackNode->find('.image')->find('img');
            $image = $imageNode->getAttribute('src');
            if (empty($image)) {
                $image = $imageNode->getAttribute('data-shz-img');
            }

            $tracks[] = new \App\Entities\Track($artist, $track, $image);
        }

        return $tracks;
    }

}