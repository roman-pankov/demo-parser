<?php

namespace App\Parser\Adapters;

use PHPHtmlParser\Dom;

abstract class HtmlAbstract implements AdapterInterface
{
    private $pageDom;
    /**
     * @var string
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Парсинг страницы
     *
     * @return array
     */
    abstract public function parse(): array;

    /**
     * Получение DOM
     *
     * @return Dom
     * @throws \Exception
     * @throws \App\Exceptions\FailParsedException
     */
    protected function getPageDom(): Dom
    {
        if ($this->pageDom === null) {
            try {
                $dom = new Dom;
                $this->pageDom = $dom->loadFromUrl($this->url, []);
            } catch (\Exception $e) {
                throw new \App\Exceptions\FailParsedException('Ошибка парсинга страницы', 0, $e);
            }
        }

        return $this->pageDom;
    }
}