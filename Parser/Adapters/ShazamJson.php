<?php

namespace App\Parser\Adapters;


class ShazamJson implements AdapterInterface
{

    private $url;

    public function __construct($url)
    {

        $this->url = $url;
    }

    /**
     * Парсинг страницы
     *
     * @return array
     * @throws \App\Exceptions\FailParsedJsonException
     */
    public function parse(): array
    {
        $json = file_get_contents($this->url);
        $response = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \App\Exceptions\FailParsedJsonException('Ошибка парсинга JSON', 0);
        }

        $tracks = [];
        foreach ($response->chart as $item) {
            if(!empty($item->images->default)) {
                $image = $item->images->default;
            }
            $tracks[] = new \App\Entities\Track($item->heading->subtitle, $item->heading->title, $image ?? null);
        }

        return $tracks;
    }
}