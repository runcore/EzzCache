<?php
namespace Ezz;

/**
 * Class CacheAPC
 * @package Ezz
 */
class CacheAPC extends Cache {

    /**
     * @return bool
     */
    protected function _expired() {
        if ( !apc_exists( $this->getKey() ) ) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    protected function _get() {
        return apc_fetch( $this->getKey() );
    }

    /**
     * @param $data
     * @return array|bool
     */
    protected function _set($data) {
        return apc_store( $this->getKey(), $data, $this->ttl );
    }

    /**
     *
     */
    protected function _delete() {
        apc_delete( $this->getKey() );
    }

}
