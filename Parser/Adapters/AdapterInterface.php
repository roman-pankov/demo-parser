<?php

namespace App\Parser\Adapters;


interface AdapterInterface
{
    /**
     * Парсинг страницы
     *
     * @return array
     */
    public function parse(): array;
}