<?php
namespace Ezz;

/**
 * Class LogFile
 * @package Ezz
 */
class LogFile
{
    //protected $fileName = 'log';

    /**
     * @param $method
     * @param $obj
     */
    public static function add($method, $obj) {
        //return null;

        $timeStamp = date('d.m.Y H:i:s');
        $fileName = date('YmdHis', $_SERVER['REQUEST_TIME']) . '.log';
        // todo: log dir ?
        $line = $timeStamp.' '. $method.'() ';
        if (is_object($obj)) {
            $line .= print_r($obj, true);
        } else if ( is_array($obj)) {
            $line .= print_r($obj, true);
        } else if ( is_bool($obj)) {
            $line .= ($obj===TRUE?'TRUE':'FALSE');
        } else {
            $line .= $obj;
        }
        $line .= PHP_EOL;
        pr($line);

        if (!file_exists($fileName)) {
//            touch($fileName);
        }
//        file_put_contents($fileName, $line, FILE_APPEND );
    }

}