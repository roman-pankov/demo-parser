<?php
require __DIR__ . '/vendor/autoload.php';

$config = [
    'url'            => [
        'alias'   => 'u',
        'default' => '',
        'help'    => 'Список URL\'ов для парсинга разделенных пробелом. Пример: php index.php -u="https://www.shazam.com/ru/charts/top-50/russia https://music.yandex.ru/chart"',
    ],
    'fork'           => [
        'alias'   => 'f',
        'default' => 1,
        'help'    => 'Количество форков. Пример: php index.php -f 2',
    ],
    'ignore-dynamic' => [
        'alias'   => 'i',
        'default' => true,
        'help'    => 'Игнорировать URL\'ы с динамическим рендерингом',
        'filter'  => 'bool',
    ]
];
$cliArgs = new \CliArgs\CliArgs($config);

// Вызов справки
if ($cliArgs->isFlagExists('help', 'h')) {
    echo $cliArgs->getHelp('help');
    die();
}

// Игнорировать URL'ы с динамическим рендерингом
$ignoreDynamic = $cliArgs->isFlagExists('ignore-dynamic', 'i');


// URL'ы
$urls = array_filter(explode(' ', $cliArgs->getArg('u')));
if (empty($urls)) {
    echo 'Необходимо указать список url\' ов через пробел' . PHP_EOL;
    echo $cliArgs->getHelp('help');
    die();
}

// Количество форков
$countForks = $cliArgs->getArg('f');
$fork = new \App\Forks\PcntlFork($countForks);
if ($countForks > 1 && $fork->isUseForks() === false) {
    echo 'Для использования форков необходим pcntl' . PHP_EOL;
    die();
}

// Выполненине парсинга
$fork->run($urls, function ($url) use ($ignoreDynamic) {
    try {
        (new \App\Services\ParserService())->stdOut($url);
    } catch (\Exception $e) {
        // @todo Запись в лог
        if ($ignoreDynamic === true) {
            echo $url . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            die();
        }
    }
});