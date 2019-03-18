<?php

namespace ssz\mobo\components;

use ssz\mobo\components\Url;

class Asset
{
    public function cacheBooster(string $path, $base_uri = false) : string
    {
        $rootDir = __DIR__ . '/../';
        $file = realpath( $rootDir . $path );
        if ( !file_exists( $file ) ) {
            throw new \Error( __METHOD__ . ' ::: file not exist ::: ' . $file );
        }

        $v = filemtime( $file );
        $uri = $path . '?v=' . $v;
        return $base_uri ? Url::base_url( $uri ) : $uri;
    }
}
