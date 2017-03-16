<?php

namespace Ezz;

/**
 * Class CacheFile
 * @package Ezz
 */
class CacheFile extends Cache {

    /**
     * @var string
     */
    protected $dir;

    /**
     * CacheFile constructor.
     * @param array $params
     * @param $cacheDir
     * @throws \Exception
     */
    public function __construct(array $params, $cacheDir) {
        parent::__construct($params);

        if ( !is_dir($cacheDir) ) {
            throw new \Exception('Invalid cache dir');
        } else {
            $this->dir = trim($cacheDir, '/\\') . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @return string
     */
    protected function getKey() {
        $file = $this->dir . $this->key;
        return $file;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function _set($data) {
        $file = $this->getKey();
        //$data = [$this->type, $data];
        return (file_put_contents( $file, $data, LOCK_EX) !== false);
    }

    /**
     * @return null|string
     */
    protected function _get() {
        $file = $this->getKey();
        return file_get_contents($file);
    }

    /**
     * @return bool
     */
    protected function _expired() {
        $file = $this->getKey();
        if (!file_exists($file)) {
            return true;
        }
        $ftime = filemtime($file);

        return ( time() > ($ftime + $this->ttl) );
    }

    /**
     *
     */
    protected function _delete() {
        $file = $this->getKey();
        if (file_exists($file)) {
            unlink($file);
        }
    }

}
