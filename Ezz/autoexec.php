<?php
namespace Ezz;

date_default_timezone_set( 'Asia/Novosibirsk' );
setlocale(LC_ALL, 'ru_RU.UTF8');
mb_internal_encoding("UTF-8");

spl_autoload_register(function ($class) {
    $class = str_replace('_',DIRECTORY_SEPARATOR, $class);
    include $class . '.php';
});

/**
 * @param array $params
 * @param $func
 * @return array
 */
function cache(Array $params, $func) {
    $cache = new CacheFile($params, './cache/');

    $data = $cache->get();
    if ( !is_null($data) ) {
        return $data;
    }
    if ( is_callable( $func ) ) {
        $data = $func();
        $cache->set( $data );
    }

    return $data;
}


function pr($a, $f=0) {
    echo '<pre>';
    print_r($a);
    echo '</pre>';
    if ($f) exit;
}