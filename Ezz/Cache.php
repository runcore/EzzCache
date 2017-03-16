<?php
namespace Ezz;

/**
 * Class Cache
 * @package Ezz
 */
abstract class Cache {

    // todo: initial cache ?

    const TYPE_OBJECT = 'object';
    const TYPE_ARRAY  = 'array';
    const TYPE_STRING = 'string';

    protected $enabled = true;
    protected $key;
    protected $ttl;
    protected $type = self::TYPE_STRING;

    const TTL_DEFAULT = 60; // seconds

    /**
     * Cache constructor.
     * @param array $params
     */
    public function __construct(Array $params) {
        if (sizeof($params)) {
            $this->setKey( array_shift($params) );
        }
        if (sizeof($params)) {
            $this->setTtl( array_shift($params) );
        }
        if (sizeof($params)) {
            $this->enabled = (bool) array_shift($params);
        }
    }

    /**
     * Get data from cache store, by key
     */
    public function get() {
        LogFile::add(__METHOD__, '' );

        // enabled ?
        if ( !$this->enabled ) {
            return null;
        }
        // expired ?
        if ( $this->expired() ) {
            return null;
        }

        $data = $this->_get();
        list($type,$data) = unserialize($data);

        return $data;
    }

    public function set( $data ) {
        // enabled ?
        if ( !$this->enabled ) {
            return null;
        }
        // type ?
        if ( is_object($data) ) {
            $this->type = self::TYPE_OBJECT;
            LogFile::add(__METHOD__, $this->type.' type: '.gettype($data) );
            //$data = serialize($data);
        } else if (is_array($data)) {
            $this->type = self::TYPE_ARRAY;
            LogFile::add(__METHOD__, $this->type.' size: '.sizeof($data) );
            //$data = serialize($data);
        } else {
            LogFile::add(__METHOD__, $data );
        }
        $data = serialize( [$this->type, $data] );
        //
        $this->_set( $data );
    }

    public function expired() {
        $expired = $this->_expired();
        LogFile::add(__METHOD__,$expired);

        return $expired;
    }

    /**
     * @return mixed
     */
    protected function getKey() {
        return $this->key;
    }

    /**
     * @param $key
     */
    protected function setKey( $key ) {
        LogFile::add(__METHOD__, $key );
        $key = trim($key);
        if (!empty($key)) {
            $this->key = $key;
        }
    }

    /**
     * @param $ttl
     */
    protected function setTtl( $ttl ) {
        LogFile::add(__METHOD__, $ttl );
        if ( !empty($ttl) && is_numeric($ttl) ) {
            $this->ttl = abs( intval($ttl) );
        } else {
            $this->ttl = self::TTL_DEFAULT;
        }
    }

    /**
     * @param $minutes
     * @return int|string
     * @throws \Exception
     */
    public static function setMinutes($minutes) {
        if (!is_numeric($minutes)) {
            throw new \Exception('Expected integer value of minutes');
        }

        return $minutes * 60;
    }

    /**
     * @param $hours
     * @return int|string
     * @throws \Exception
     */
    public static function setHours($hours) {
        if (!is_numeric($hours)) {
            throw new \Exception('Expected integer value of hours');
        }

        return $hours * 3600;
    }

    // ABSTRACTS

    /**
     * Get data from cache-store
     * @return mixed
     */
    abstract protected function _get();

    /**
     * @param $data
     * @return mixed
     */
    abstract protected function _set( $data );

    /**
     * @return mixed
     */
    abstract protected function _expired();

}
