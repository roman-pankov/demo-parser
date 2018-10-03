Установка
---


### Шаг 1
Для парсинга с рендерингом на JS необходимы зависимости:
Установить nodejs (в зависимости от ОС) после чего выполнить следующее:

```
$ npm install -g phantomjs
$ npm install -g casperjs
```
Если парсинга с рендерингом на JS не требуется, переходите к шагу 2(!)

### Шаг 2

Установить composer

### Шаг 3

```
$ composer install
```

Испльзование
---

##### Вызов справки
```
php index.php --help
```

##### Задание URL'ов для парсинга
```
php index.php --url "https://www.shazam.com/ru/charts/top-50/russia https://www.shazam.com/shazam/v2/ru/RU/web/-/tracks/country-chart-RU?r=17805 https://music.yandex.ru/chart"
```

##### Задание указание количества форков
```
php index.php --fork 2 --url "https://www.shazam.com/ru/charts/top-50/russia https://www.shazam.com/shazam/v2/ru/RU/web/-/tracks/country-chart-RU?r=17805 https://music.yandex.ru/chart"
```


Создание своих парсеров
---

Все парсеры храняться в папке `Parser/Adapters`. Все парсеры реализуют интерйфес `App\Parser\Adapters` и имею метод `parse` в котором находится вся логика парсинга и он должен возвращать массив сущностей `App\Entities\Track`

Для удобства, было реализовано два абстаркных класса которые реализуют метод `getPageDom` для получение объекта `PHPHtmlParser\Dom` для работы с DOM деревом.
* Для статических HTML страниц можно унаследоваться от абстракного класса `App\Parser\Adapters\HtmlAbstract`
* Для динамических HTML страниц можно унаследоваться от абстракного класса `App\Parser\Adapters\HtmlDynamicAbstract`

Для создания объекта парсера в системе используется фабрика `App\Parser\ParserFactory`. Она отвечает за маппинг URL'а с определённым парсером.

Для добавления своего маппинга необходимо добавить соответсвующий класс в приватную переменную `$adapters` и прописать регулярное выражение для URL'а