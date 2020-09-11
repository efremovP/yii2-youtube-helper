Скачивание видео с каналов YouTube на свой сайт
===============================================
Скачиваем все видео с плейлиста YouTube с помощью API. Для дальнейшего сохранения ссылок на видео в своей БД. И вывода их на сайт.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist efremovp/yii2-youtube-helper "*"
```

or add

```
"efremovp/yii2-youtube-helper": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
use efremovP\youtube\ApiYouTube;


$youtube = new ApiYouTube('Youtube_Api_Key');

$id_youtube_playlist = 'PLdmSK1Qzu984Jnm_YhDcD_Hs5WEB39HoR';
// скачать список видео плейлиста
$video_list = $youtube->getList($id_youtube_playlist);

// получаем иконку на видео
$key_video = '7JHQ83gho6E';
$image_url = $youtube->getImage($key_video);

```