<?php

namespace App\Parser\Adapters;

use PHPHtmlParser\Dom;
use Browser\Casper;

abstract class HtmlDynamicAbstract implements AdapterInterface
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
                $casper = new Casper();
                $casper->setOptions(array('ignore-ssl-errors' => 'yes'));
                $casper->start($this->url);
                $casper->wait(6000);
                $casper->run();
                $html = $casper->getHtml();
                $dom = new Dom;

                $this->pageDom = $dom->loadStr($html, []);
            } catch (\Exception $e) {
                throw new \App\Exceptions\FailParsedException('Ошибка парсинга страницы', 0, $e);
            }
        }

        return $this->pageDom;
    }
}