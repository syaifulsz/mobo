<?php

namespace ssz\mobo\components;

use app\components\{
    Str
};

class Cache
{
    private $m;

    public function __construct(  )
    {
        $this->m = new \Memcached();
        $this->m->addServer( $this->getCacheConfig( 'host' ), $this->getCacheConfig( 'port' ) );
    }

    private function getCacheConfig( string $key = '' )
    {
        $config = require __DIR__ . '/../configs/memcached.php';
        if ( $projectId = getenv( 'PROJECT_ID' ) ) {
            $siteConfig = __DIR__ . '/../../sites/' . $projectId . '/configs/memcached.php';
            if ( file_exists( $siteConfig ) ) {
                $config = array_replace_recursive( $config, require $siteConfig );
            }
        }

        if ( $key ) {
            return data_get( $config, $key );
        }

        return $config;
    }

    public function set( $key, $cacheData, $expire = false )
    {
        return $this->m->set( $key, $cacheData, $expire );
    }

    public function get( $key )
    {
        return $this->m->get( $key );
    }

    public function remove( $key )
    {
        return $this->m->delete( $key );
    }

    public function removeAll()
    {
        return $this->m->flush();
    }

    public function createKey( $keys, $md5 = true )
    {
        if ( is_array( $keys ) ) {
            $keys = http_build_query( $keys );
        }

        if ( !$md5 ) {
            return Str::slugify( urldecode( $keys ), '-' );
        }

        return md5( $keys );
    }
}
