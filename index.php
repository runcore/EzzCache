<?php

require_once('./Ezz/autoexec.php');

// Examples

$page = 3;
$cacheKey = 'news_page'.$page;
$ttl = 3;
$params = [$cacheKey,$ttl];
$newsList = Ezz\cache($params, function() use ($page) {
    $news = [
        'Data from DB '.time()
        ,['Another data from DB '.time()]
        ,['Big data from DB '.time()=>'12323123']
        ,new Ezz\CacheFile(['Key1',60],'./cache/')
    ];
    if (isset($news[$page])) {
        return $news[$page];
    }
    return null;
});

// Show result
Ezz\pr( $newsList );

