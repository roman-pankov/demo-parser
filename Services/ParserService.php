<?php

namespace App\Services;

class ParserService
{
    /**
     * В поток
     * @param $urls
     * @throws \App\Exceptions\ParserAdapterNotFoundException
     */
    public function stdOut($urls): void
    {
        if (!\is_array($urls)) {
            $urls = (array) $urls;
        }
        foreach ($this->parseUrls($urls) as $url => $tracks) {
            echo '*** URL:' . $url . ' ***' . PHP_EOL;
            /**
             * @var $track \App\Entities\Track
             */
            foreach ($tracks as $track) {
                echo print_r($track->toArray(), true) . PHP_EOL;
            }
        }
    }

    /**
     * В CSV файл
     *
     * @param $urls
     * @throws \App\Exceptions\ParserAdapterNotFoundException
     */
    public function csv($urls): void
    {
        foreach ($this->parseUrls($urls) as $url => $tracks) {
            // @todo Сохранение в csv
        }
    }

    /**
     * Парсинг URL'ов
     *
     * @param $urls
     * @return array
     * @throws \App\Exceptions\ParserAdapterNotFoundException
     */
    private function parseUrls($urls): array
    {
        $result = [];
        foreach ($urls as $url) {
            $parserFactory = new \App\Parser\ParserFactory();
            $parser = $parserFactory->createFromUrl($url);

            $result[$url] = $parser->parse();
        }

        return $result;
    }
}